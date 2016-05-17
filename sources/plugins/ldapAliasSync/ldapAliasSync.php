<?php
/*
 * LDAP Alias Sync: Syncronize users' identities (name, email, organization, reply-to, bcc, signature)
 * by querying an LDAP server's aliasses.
 *
 * Based on the 'IdentiTeam' Plugin by André Rodier <andre.rodier@gmail.com>
 * Author: Lukas Mika <lukas.mika@web.de>
 * Licence: GPLv3. (See copying)
 */
class ldapAliasSync extends rcube_plugin {
// ---------- Global variables
	// Internal variables
	public  $task = 'login';
	private $initialised;
	private $app;
	private $rc_user;

	// Config variables
	private $config      = array();
	private $cfg_ldap    = array();
	private $cfg_mail    = array();
	private $cfg_user    = array();
	private $cfg_alias   = array();
	private $cfg_general = array();

	// Plugin variables
	private $ldap_con;

// ---------- Main functions
	// Plugin initialization
	function init() {
		try {
			$this->log_debug('Initialising...');

			// Load general roundcube config settings
			$this->load_config('config.inc.php');
			$this->app = rcmail::get_instance();

			// Load plugin config settings
			$this->config = $this->app->config->get('ldapAliasSync');

			$this->cfg_ldap	   = $this->check_ldap_config($this->config['ldap']);
			$this->cfg_mail	   = $this->check_mail_config($this->config['mail']);
			$this->cfg_user	   = $this->check_user_config($this->config['user_search']);
			$this->cfg_alias   = $this->check_alias_config($this->config['alias_search']);
			$this->cfg_general = $this->check_general_config($this->config['general']);
			$this->log_debug('Configuration successfully loaded');

			// register hook
			$this->add_hook('login_after', array($this, 'login_after'));
			$this->initialised = true;
			$this->log_debug('Plugin Hook \'login_after\' set');
		} catch ( Exception $exc ) {
			$this->log_error('Failed to initialise: '.$exc->getMessage());
		}

		if ( $this->initialised ) {
			$this->log_info('Plugin initialised');
		}
	}

	/**
	 * login_after
	 *
	 * See http://trac.roundcube.net/wiki/Plugin_Hooks
	 * Arguments:
	 * - URL parameters (e.g. task, action, etc.)
	 * Return values:
	 * - task
	 * - action
	 * - more URL parameters
	 */
	function login_after($args) {
		$login = array();

		try {
			$this->rc_user = rcmail::get_instance()->user;
			$login = $this->get_login_info($this->rc_user->get_username('mail'), $this->cfg_mail);
			$this->log_debug("User information: %login: ".$login['login'].", %local: ".$login['local'].", %domain: ".$login['domain'].", %email: ".$login['email']);

			$this->ldap_con  = $this->initialize_ldap($this->cfg_ldap);
			$this->log_debug("LDAP connection established");

			$identities = $this->fetch_identities($login);
			$this->log_info("Fetched ".count($identities)." identities");

			$this->sync_identities_db($identities);
			$this->log_info("Identities in DB synchronized");
		} catch ( Exception $exc ) {
			$this->log_error('Runtime error: '.$exc->getMessage());
		}

		ldap_close($this->ldap_con);
		$this->log_debug("LDAP connection closed");

		return $args;
	}

// ---------- Helper functions
	function initialize_ldap($config) {
		$uri = '';
		$con = null;

		$uri = $config['scheme'].'://'.$config['server'].':'.$config['port'];

		$con = ldap_connect($uri);

		if ( is_resource($con) ) {
			$this->log_debug("LDAP resource: ".$uri);
			ldap_set_option($con, LDAP_OPT_PROTOCOL_VERSION, 3);
		} else {
			throw new Exception(sprintf("Connection to the server failed: (Error=%s)", ldap_errno($con)));
		}

		// Bind to server
		if ( $this->cfg_ldap['bind_dn'] ){
			$bound = @ldap_bind($con, $this->cfg_ldap['bind_dn'], $this->cfg_ldap['bind_pw']);
		} else {
			$bound = @ldap_bind($con);
                }

		if ( ! $bound ) {
			throw new Exception(sprintf("Bind to server '%s' failed. Con: (%s), Error: (%s)", $this->cfg_ldap['server'], $con, ldap_errno($con)));
		} else {
			$this->log_debug("LDAP Bind successfull");
		}

		return $con;
	}

	function get_login_info($info, $config) {
		$login = array();

		$login['login'] = $info;

		if ( strstr($info, '@') ) {
			$login_parts = explode('@', $info);

			$login['local'] = $login_parts[0];
			$login['domain'] = $login_parts[1];

			if ( $config['replace_domain'] && $config['search_domain'] ) {
				$login['domain'] = $config['search_domain'];
			}
		} else {
			$login['local'] = $login;

			if ( $config['search_domain'] ) {
				$login['domain'] = $config['search_domain'];
			}
		}

		if ( $config['dovecot_separator'] && strstr($login['local'], $config['dovecot_separator']) ) {
			$login['local'] = array_shift(explode($config['dovecot_separator'], $login['local']));
		}

		if ( $login['local'] && $login['domain'] ) {
			$login['email'] = $login['local']."@".$login['domain'];
		}

		return $login;
	}

	function fetch_identities($login) {
		$ldap_users = array();
		$ldap_user  = array();
		$aliases    = array();
		$alias      = array();
		$identities = array();

		$ldap_users = $this->get_ldap_identities($login, $this->cfg_user, '');

		if ( count($ldap_users) == 0 ) {
			throw new Exception(sprintf("User '%s' not found.", $login['login']));
		} else {
			$this->log_debug("Found ".count($ldap_users)." for user ".$login['login']);
		}

		foreach ( $ldap_users as $ldap_user ) {
			array_push($identities, $ldap_user);
			$aliases = $this->get_ldap_identities($login, $this->cfg_alias, $ldap_user['dn']);
			$this->log_debug("Found ".count($aliases)." aliases");
			foreach ( $aliases as $alias ) {
				array_push($identities, $alias);
			}
		}

		return $identities;
	}

	function get_ldap_identities($login, $config, $dn) {
		$base_dn    = $config['base_dn'];
		$filter     = $config['filter'];
		$fields     = array();
		$bound      = false;
		$result     = null;
		$entries    = array();
		$identities = array();

		// Prepare LDAP query base DN
		$base_dn = str_replace('%login', $login['login'], $base_dn);
		$base_dn = str_replace('%local', $login['local'], $base_dn);
		$base_dn = str_replace('%domain', $login['domain'], $base_dn);
		$base_dn = str_replace('%email', $login['email'], $base_dn);
		$base_dn = str_replace('%dn', $dn, $base_dn);
		$base_dn = str_replace('%%', '%', $base_dn);

		// Prepare LDAP query filter
		$filter = str_replace('%login', $login['login'], $filter);
		$filter = str_replace('%local', $login['local'], $filter);
		$filter = str_replace('%domain', $login['domain'], $filter);
		$filter = str_replace('%email', $login['email'], $filter);
		$filter = str_replace('%dn', $dn, $filter);
		$filter = str_replace('%%', '%', $filter);

		// Prepare LDAP query attributes
		if ( $config['attr_mail'] ) {
			array_push($fields, $config['attr_mail']);
		}
		if ( $config['attr_local'] ) {
			array_push($fields, $config['attr_local']);
		}
		if ( $config['attr_dom'] ) {
			array_push($fields, $config['attr_dom']);
		}
		if ( $config['attr_name'] ) {
			array_push($fields, $config['attr_name']);
		}
		if ( $config['attr_org'] ) {
			array_push($fields, $config['attr_org']);
		}
		if ( $config['attr_reply'] ) {
			array_push($fields, $config['attr_reply']);
		}
		if ( $config['attr_bcc'] ) {
			array_push($fields, $config['attr_bcc']);
		}
		if ( $config['attr_sig'] ) {
			array_push($fields, $config['attr_sig']);
		}
		if ( $config['mail_by'] == 'memberof' ) {
			array_push($fields, 'memberof');
		}

		$this->log_debug("LDAP Query: base DN: ".$base_dn.", filter: ".$filter);
		$result = @ldap_search($this->ldap_con, $base_dn, $filter, $fields, 0, 0, 0, $config['deref']);

		if ( $result ) {
			$entries = @ldap_get_entries($this->ldap_con, $result);
		} else {
			$this->log_warning("LDAP Message: ".ldap_errno($this->ldap_con).": ".ldap_error($this->ldap_con));
		}

		$this->log_debug("LDAP Query returned ".$entries['count']." entries.");
		for ($i=0; $i<$entries['count']; $i++) {
			$entry = null;
			$entry = $entries["$i"];
			$ids = $this->get_ids_from_obj($entry, $config);
			$this->log_debug(count($ids)." IDs fetched");
			foreach ( $ids as $id ) {
				array_push($identities, $id);
			}
		}
		$this->log_debug(count($identities)." IDs fetched in total for this LDAP query");
		return $identities;
	}

	function get_ids_from_obj($ldap_id, $config) {
		$identity   = array();
		$identities = array();
		$entries    = array();
		$entry      = array();
		$local      = '';
		$domain     = '';
		$stop       = true;

		// Get attributes
		$identity['dn'] = $ldap_id['dn'];

		if ( $config['attr_name'] ) {
			$ldap_temp = $ldap_id[$config['attr_name']];
			$identity['name'] = $ldap_temp[0];
		}

		if ( $config['attr_org'] ) {
			$ldap_temp = $ldap_id[$config['attr_org']];
			$identity['organization'] = $ldap_temp[0];
		}

		if ( $config['attr_reply'] ) {
			$ldap_temp = $ldap_id[$config['attr_reply']];
			$identity['reply-to'] = $ldap_temp[0];
		}

		if ( $config['attr_bcc'] ) {
			$ldap_temp = $ldap_id[$config['attr_bcc']];
			$identity['bcc'] = $ldap_temp[0];
		}

		if ( $config['attr_sig'] ) {
			$ldap_temp = $ldap_id[$config['attr_sig']];
			$identity['signature'] = $ldap_temp[0];
		}

		if ( preg_match('/^\s*<[a-zA-Z]+/', $identity['signature']) ) {
			$identity['html_signature'] = 1;
		} else {
			$identity['html_signature'] = 0;
		}

		// Get e-mail address
		switch ( $config['mail_by'] ) {
			case 'attribute':
				$ldap_temp = $ldap_id[$config['attr_mail']];
				foreach ( $ldap_temp as $attr ) {
					if ( strstr($attr, '@') ) {
						$domain_expl = explode('@', $attr);
						$domain = $domain_expl[1];
						if ( $domain && ! in_array( $domain, $config['ignore_domains']) ) {
							$identity['email'] = $attr;
							if ( ! $identity['name'] ) {
								$identity_expl = explode('@', $attr);
								$identity['name'] = $identity_expl[0];
							}
							array_push($identities, $identity);
							$this->log_debug("Found address ".$identity['email']);
						}
					}
				}
				break;
			case 'dn':
				$ldap_temp = $ldap_id[$config['attr_local']];
				$local = $ldap_temp[0];
				if ( $config['non_domain_attr'] == 'skip' ) {
					$stop = false;
				} else {
					$stop = true;
				}
				$domain = $this->get_domain_name($ldap_id['dn'], $config['attr_dom'], $stop);
				if ( $local && $domain && ! in_array($domain, $config['ignore_domains']) ) {
					$identity['email'] = $local.'@'.$domain;
					if ( ! $identity['name'] ) {
						$identity['name'] = $local;
					}
					array_push($identities, $identity);
					$this->log_debug("Found address ".$identity['email']);
				}
				break;
			case 'memberof':
				$ldap_temp = $ldap_id[$config['attr_local']];
				$local = $ldap_temp[0];
				if ( $config['non_domain_attr'] == 'skip' ) {
					$stop = false;
				} else {
					$stop = true;
				}
				$ldap_temp = $ldap_id['memberof'];
				foreach ( $ldap_temp as $memberof ) {
					$domain = $this->get_domain_name($memberof, $config['attr_dom'], $stop);
					if ( $local && $domain && ! in_array($domain, $config['ignore_domains']) ) {
						$identity['email'] = $local.'@'.$domain;
						if ( ! $identity['name'] ) {
							$identity['name'] = $local;
						}
						array_push($identities, $identity);
						$this->log_debug("Found address ".$identity['email']);
					}
				}
				break;
			case 'static':
				$ldap_temp = $ldap_id[$config['attr_local']];
				$local = $ldap_temp[0];
				if ( $local && $config['domain_static'] && ! in_array($config['domain_static'], $config['ignore_domains']) ) {
					$identity['email'] = $local.'@'.$config['domain_static'];
					if ( ! $identity['name'] ) {
						$identity['name'] = $local;
					}
					array_push($identities, $identity);
					$this->log_debug("Found address ".$identity['email']);
				}
				break;
		}
		return $identities;
	}

	function get_domain_name( $dn, $attr, $stop = true ) {
    		$found = false;
		$domain = '';

		$dn_parts = explode(',', $dn);

		foreach( $dn_parts as $dn_part ) {
			$objs = explode('=', $dn_part);
			if ($objs[0] == $attr) {
				$found = true;
				if ( strlen( $domain ) == 0 ) {
					$domain = $objs[1];
				} else {
					$domain .= ".".$objs[1];
				}
			} elseif ( $found == true && $stop == true ) {
				break;
			}
		}
		return $domain;
	}

	function sync_identities_db($identities) {
		$db_identities = array();
		$db_identity   = array();
		$identity      = array();
		$key           = '';
		$value         = '';
		$in_db         = false;
		$in_ldap       = false;

		if ( count($identities) > 0 && $db_identities = $this->rc_user->list_identities() ) {

			# Check which identities not yet contained in the database
			foreach ( $identities as $identity ) {
				$in_db = false;
				unset($identity['dn']);

				foreach ( $db_identities as $db_identity ) {
					# email is our only comparison parameter
					if( $db_identity['email'] == $identity['email'] ) {
						if ( $this->cfg_general['update_existing'] ) {
							if ( ! $this->cfg_general['update_empty_fields']) {
								foreach ($identity as $key => $value) {
									if ( empty($identity[$key]) ) {
										unset($identity[$key]);
									}
								}
							}
							$this->rc_user->update_identity ( $db_identity['identity_id'], $identity );
							$this->log_info("Updated identity ".$identity['email']);
						}
						$in_db = true;
						break;
					}
				}

				if( !$in_db ) {
					$this->rc_user->insert_identity( $identity );
					$this->log_info("Inserted identity ".$identity['email']);
				}
			}

			# Check which identities are available in database but nut in LDAP and delete those
			foreach ( $db_identities as $db_identity ) {
				$in_ldap = false;

				foreach ( $identities as $identity ) {
					# email is our only comparison parameter
					if( $db_identity['email'] == $identity['email'] ) {
						$in_ldap = true;
						break;
					}
				}

				# If this identity does not exist in LDAP, delete it from database
				if( !$in_ldap ) {
					$this->rc_user->delete_identity($db_identity['identity_id']);
					$this->log_warning("Deleted identity ".$db_identity['email']);
				}
			}
		}
	}

	function log_error($msg) {
		write_log('ldapAliasSync', "ERROR: ".$msg);
	}

	function log_warning($msg) {
		if ( $this->cfg_general['log_level'] >= 1 ) {
			write_log('ldapAliasSync', "WARNING: ".$msg);
		}
	}

	function log_info($msg) {
		if ( $this->cfg_general['log_level'] >= 2 ) {
			write_log('ldapAliasSync', "INFO: ".$msg);
		}
	}

	function log_debug($msg) {
		if ( $this->cfg_general['log_level'] >= 3 ) {
			write_log('ldapAliasSync', "DEBUG: ".$msg);
		}
	}

// ---------- Configuration functions
	function check_ldap_config($config) {
		$SCHEMES = array('ldap', 'ldaps', 'ldapi');

		// Set default values for empty config parameters
		if (! $config['scheme']) {
			$config['scheme'] = 'ldap';
		}
		if (! $config['server']) {
			$config['server'] = 'localhost';
		}
		if (! $config['port']) {
			$config['port'] = '389';
		}
		if (! $config['bind_dn']) {
			$config['bind_dn'] = '';
		}
		if (! $config['bind_pw']) {
			$config['bind_pw'] = '';
		}

		// Check parameters with fixed value set
		if (! in_array($config['scheme'], $SCHEMES)) {
			throw new Exception('[ldap] scheme "'.$config['scheme'].'" is invalid');
		}

		return $config;
	}

	function check_mail_config($config) {
		// Set default values for empty config parameters
		if (! $config['search_domain']) {
			$config['search_domain'] = '';
		}
		if (! $config['replace_domain']) {
			$config['replace_domain'] = false;
		}
		if (! $config['dovecot_separator']) {
			$config['dovecot_separator'] = '';
		}

		// Check parameter combinations
		if ($config['replace_domain'] && ! $config['search_domain']) {
			throw new Exception('[mail] search_domain must not be initial, if replace_domain is set to "true"!');
		}

		return $config;
	}

	function check_user_config($config) {
		$DEREFS   = array($LDAP_DEREF_NEVER, $LDAP_DEREF_FINDING, $LDAP_DEREF_SEARCHING, $LDAP_DEREF_ALWAYS);
		$MAIL_BYS = array('attribute', 'dn', 'memberof', 'static');
		$NDATTRS  = array('stop', 'skip');

		// Set default values for empty config parameters
		if (! $config['base_dn']) {
			$config['base_dn'] = '';
		}
		if (! $config['filter']) {
			$config['filter'] = '(objectClass=*)';
		}
		if (! $config['deref']) {
			$config['deref'] = 'never';
		}
		if (! $config['mail_by']) {
			$config['mail_by'] = 'attribute';
		}
		if (! $config['attr_mail']) {
			$config['attr_mail'] = 'mail';
		} else {
			$config['attr_mail'] = strtolower($config['attr_mail']);
		}
		if (! $config['attr_local']) {
			$config['attr_local'] = '';
		} else {
			$config['attr_local'] = strtolower($config['attr_local']);
		}
		if (! $config['attr_dom']) {
			$config['attr_dom'] = '';
		} else {
			$config['attr_dom'] = strtolower($config['attr_dom']);
		}
		if (! $config['domain_static']) {
			$config['domain_static'] = '';
		}
		if (! $config['ignore_domains']) {
			$config['ignore_domains'] = array();
		}
		if (! $config['non_domain_attr']) {
			$config['non_domain_attr'] = 'stop';
		}
		if (! $config['attr_name']) {
			$config['attr_name'] = '';
		} else {
			$config['attr_name'] = strtolower($config['attr_name']);
		}
		if (! $config['attr_org']) {
			$config['attr_org'] = '';
		} else {
			$config['attr_org'] = strtolower($config['attr_org']);
		}
		if (! $config['attr_reply']) {
			$config['attr_reply'] = '';
		} else {
			$config['attr_reply'] = strtolower($config['attr_reply']);
		}
		if (! $config['attr_bcc']) {
			$config['attr_bcc'] = '';
		} else {
			$config['attr_bcc'] = strtolower($config['attr_bcc']);
		}
		if (! $config['attr_sig']) {
			$config['attr_sig'] = '';
		} else {
			$config['attr_sig'] = strtolower($config['attr_sig']);
		}

		// Override values
		switch ( $config['deref'] ) {
			case 'never':
				$config['deref'] = $LDAP_DEREF_NEVER;
				break;
			case 'search':
				$config['deref'] = $LDAP_DEREF_SEARCHING;
				break;
			case 'find':
				$config['deref'] = $LDAP_DEREF_FINDING;
				break;
			case 'always':
				$config['deref'] = $LDAP_DEREF_ALWAYS;
				break;
		}

		// Check on empty parameters
		if (! $config['base_dn']) {
			throw new Exception('[user_search] base_dn must not be initial!');
		}

		// Check parameters with fixed value set
		if (! in_array($config['deref'], $DEREFS)) {
			throw new Exception('[user_search] deref "'.$config['deref'].'" is invalid');
		}
		if (! in_array($config['mail_by'], $MAIL_BYS)) {
			throw new Exception('[user_search] mail_by "'.$config['mail_by'].'" is invalid');
		}
		if (! in_array($config['non_domain_attr'], $NDATTRS)) {
			throw new Exception('[user_search] non_domain_attr "'.$config['non_domain_attr'].'" is invalid');
		}

		// Check parameter combinations
		if ($config['mail_by'] == 'attribute' && ! $config['attr_mail']) {
			throw new Exception('[user_search] attr_mail must not be initial, if mail_by is set to "attribute"!');
		}
		if ($config['mail_by'] == 'dn' && ! $config['attr_local']) {
			throw new Exception('[user_search] attr_local must not be initial, if mail_by is set to "dn"!');
		}
		if ($config['mail_by'] == 'dn' && ! $config['attr_dom']) {
			throw new Exception('[user_search] attr_dom must not be initial, if mail_by is set to "dn"!');
		}
		if ($config['mail_by'] == 'memberof' && ! $config['attr_local']) {
			throw new Exception('[user_search] attr_local must not be initial, if mail_by is set to "memberof"!');
		}
		if ($config['mail_by'] == 'memberof' && ! $config['attr_dom']) {
			throw new Exception('[user_search] attr_dom must not be initial, if mail_by is set to "memberof"!');
		}
		if ($config['mail_by'] == 'static' && ! $config['attr_local']) {
			throw new Exception('[user_search] attr_local must not be initial, if mail_by is set to "static"!');
		}
		if ($config['mail_by'] == 'static' && ! $config['domain_static']) {
			throw new Exception('[user_search] domain_static must not be initial, if mail_by is set to "static"!');
		}

		return $config;
	}

	function check_alias_config($config) {
		$DEREFS   = array($LDAP_DEREF_NEVER, $LDAP_DEREF_SEARCHING, $LDAP_DEREF_FINDING, $LDAP_DEREF_ALWAYS);
		$MAIL_BYS = array('attribute', 'dn', 'memberof', 'static');
		$NDATTRS  = array('stop', 'skip');

		// Set default values for empty config parameters
		if (! $config['base_dn']) {
			$config['base_dn'] = '';
		}
		if (! $config['filter']) {
			$config['filter'] = '(objectClass=*)';
		}
		if (! $config['deref']) {
			$config['deref'] = 'never';
		}
		if (! $config['mail_by']) {
			$config['mail_by'] = 'attribute';
		}
		if (! $config['attr_mail']) {
			$config['attr_mail'] = 'mail';
		} else {
			$config['attr_mail'] = strtolower($config['attr_mail']);
		}
		if (! $config['attr_local']) {
			$config['attr_local'] = '';
		} else {
			$config['attr_local'] = strtolower($config['attr_local']);
		}
		if (! $config['attr_dom']) {
			$config['attr_dom'] = '';
		} else {
			$config['attr_dom'] = strtolower($config['attr_dom']);
		}
		if (! $config['domain_static']) {
			$config['domain_static'] = '';
		}
		if (! $config['ignore_domains']) {
			$config['ignore_domains'] = array();
		}
		if (! $config['non_domain_attr']) {
			$config['non_domain_attr'] = 'stop';
		}
		if (! $config['attr_name']) {
			$config['attr_name'] = '';
		} else {
			$config['attr_name'] = strtolower($config['attr_name']);
		}
		if (! $config['attr_org']) {
			$config['attr_org'] = '';
		} else {
			$config['attr_org'] = strtolower($config['attr_org']);
		}
		if (! $config['attr_reply']) {
			$config['attr_reply'] = '';
		} else {
			$config['attr_reply'] = strtolower($config['attr_reply']);
		}
		if (! $config['attr_bcc']) {
			$config['attr_bcc'] = '';
		} else {
			$config['attr_bcc'] = strtolower($config['attr_bcc']);
		}
		if (! $config['attr_sig']) {
			$config['attr_sig'] = '';
		} else {
			$config['attr_sig'] = strtolower($config['attr_sig']);
		}

		// Override values
		switch ( $config['deref'] ) {
			case 'never':
				$config['deref'] = $LDAP_DEREF_NEVER;
				break;
			case 'search':
				$config['deref'] = $LDAP_DEREF_SEARCHING;
				break;
			case 'find':
				$config['deref'] = $LDAP_DEREF_FINDING;
				break;
			case 'always':
				$config['deref'] = $LDAP_DEREF_ALWAYS;
				break;
		}

		// Check on empty parameters
		if (! $config['base_dn']) {
			throw new Exception('[alias_search] base_dn must not be initial!');
		}

		// Check parameters with fixed value set
		if (! in_array($config['deref'], $DEREFS)) {
			throw new Exception('[alias_search] deref "'.$config['deref'].'" is invalid');
		}
		if (! in_array($config['mail_by'], $MAIL_BYS)) {
			throw new Exception('[alias_search] mail_by "'.$config['mail_by'].'" is invalid');
		}
		if (! in_array($config['non_domain_attr'], $NDATTRS)) {
			throw new Exception('[alias_search] non_domain_attr "'.$config['non_domain_attr'].'" is invalid');
		}

		// Check parameter combinations
		if ($config['mail_by'] == 'attribute' && ! $config['attr_mail']) {
			throw new Exception('[alias_search] attr_mail must not be initial, if mail_by is set to "attribute"!');
		}
		if ($config['mail_by'] == 'dn' && ! $config['attr_local']) {
			throw new Exception('[alias_search] attr_local must not be initial, if mail_by is set to "dn"!');
		}
		if ($config['mail_by'] == 'dn' && ! $config['attr_dom']) {
			throw new Exception('[alias_search] attr_dom must not be initial, if mail_by is set to "dn"!');
		}
		if ($config['mail_by'] == 'memberof' && ! $config['attr_local']) {
			throw new Exception('[alias_search] attr_local must not be initial, if mail_by is set to "memberof"!');
		}
		if ($config['mail_by'] == 'memberof' && ! $config['attr_dom']) {
			throw new Exception('[alias_search] attr_dom must not be initial, if mail_by is set to "memberof"!');
		}
		if ($config['mail_by'] == 'static' && ! $config['attr_local']) {
			throw new Exception('[alias_search] attr_local must not be initial, if mail_by is set to "static"!');
		}
		if ($config['mail_by'] == 'static' && ! $config['domain_static']) {
			throw new Exception('[alias_search] domain_static must not be initial, if mail_by is set to "static"!');
		}

		return $config;
	}

	function check_general_config($config) {
		$LOG_LEVELS = array(3, 2, 1, 0);

		// Set default values for empty parameters
		if (! $config['log_level']) {
			$config['log_level'] = 'error';
		}
		if (! $config['update_existing']) {
			$config['update_existing'] = false;
		}
		if (! $config['update_empty_fields']) {
			$config['update_empty_fields'] = false;
		}

                // Override values
                switch ( $config['log_level'] ) {
                        case 'debug':
                                $config['log_level'] = 3;
                                break;
                        case 'info':
                                $config['log_level'] = 2;
                                break;
                        case 'warning':
                                $config['log_level'] = 1;
                                break;
                        case 'error':
                                $config['log_level'] = 0;
                                break;
                }

		// Check parameters with fixed value set
                if (! in_array($config['log_level'], $LOG_LEVELS)) {
                        throw new Exception('[general] log_level "'.$config['log_level'].'" is invalid');
                }

		return $config;
	}
}
?>
