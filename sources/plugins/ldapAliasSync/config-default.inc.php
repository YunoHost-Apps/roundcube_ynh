<?php
/*
 * Default configuration settings for ldapAliasSync roundcube plugin
 * Copy this file in config.inc.php, and override the values you need.
*/

$rcmail_config['ldapAliasSync'] = array(
	// Mail parameters
	'mail' => array(
		# Domain to use for LDAP searches (required, if 'replace_domain' is true)
		# If no login name is given (or 'replace_domain' is true),
		# the domain part for the LDAP filter is set to this value
		# Default: none
		#'search_domain' => '',

		# Replace domain part for LDAP searches (optional)
		# This parameter can be used in order to override the login domain part with
		# the value maintained in 'search_domain'
		# Possible values: true, false
		# Default: false
		#'replace_domain' => false,

		# Dovecot master user separator (optional)
		# If you use the dovecot impersonation feature, this separator will be used
		# in order to determine the actual login name.
		# Set it to the same character if using this feature, otherwise you can also
		# leave it empty.
		# Default: none
		#'dovecot_separator' => '',
	),

	// LDAP parameters
	'ldap' => array(
		# LDAP connection scheme (optional)
		# Possible values: 'ldap', 'ldaps', 'ldapi'
		# Default: 'ldap'
		#'scheme' => 'ldap',

		# LDAP server address (optional)
		# Default: 'localhost'
		#'server' => 'localhost',

		# LDAP server port (optional)
		# Default: '389'
		#'port' => '389',

		# LDAP Bind DN (requried, if no anonymous read rights are set for the accounts)
		# Default: none
		#'bind_dn' => '',

		# Bind password (required, if the bind DN needs to authenticate)
		# Default: none
		#'bind_pw' => '',
	),

	# 'user_search' holds all config variables for the user search
	'user_search' => array(
		# LDAP search base (required)
		# - Use '%login' as a place holder for the login name
		# - Use '%local' as a place holder for the login name local part
		# - Use '%domain' as a place holder for the login name domain part (/'search_domain', if not given or replaced)
		# - Use '%email' as a place holder for the email address ('%local'@'%domain')
		# - Use '%%' as a place holder for '%'
		'base_dn' => 'uid=%local,ou=users,dc=example,dc=com',

		# LDAP search filter (optional)
		# This open filter possibility is the heart of the LDAP search.
		# - Use '%login' as a place holder for the login name
		# - Use '%local' as a place holder for the login name local part
		# - Use '%domain' as a place holder for the login name domain part (/'search_domain', if not given or replaced)
		# - Use '%email' as a place holder for the email address ('%local'@'%domain')
		# - Use '%%' as a place holder for '%'
		# Default: '(objectClass=*)'
		#'filter' => '(objectClass=*)',

		# LDAP alias derefencing (optional)
		# Possible values: never, search, find, always
		# Default: 'never'
		#'deref' => 'never',

		# How to find the e-mail addresses (required)
		# Possible values are:
		# - 'attribute' - e-mail address will be taken from the entry's 'attr_mail' attribute
		# - 'dn'        - e-mail address local part will be taken from the entry's 'attr_local';
		#                 e-mail address domain part will be taken from the DN's 'attr_dom' attributes
		# - 'memberof'  - e-mail address local part will be taken form the entry's 'attr_local';
		#                 e-mail address domain part will be taken from the memberof attributes' 'attr_dom' attributes
		# - 'static'    - e-mail address local part will be taken form the entry's 'attr_local';
		#                 e-mail address domain part will be copied from 'domain_static'
		'mail_by' => 'attribute',

		# LDAP e-mail attribute (required, if 'mail_by' is 'attribute')
		'attr_mail' => 'mail',

		# LDAP e-mail local part attribute (required, if 'mail_by' is 'dn', 'memberof' or 'static')
		# Default: none
		#'attr_local' => '',

		# LDAP e-mail domain part attribute (required, if 'mail_by' is 'dn' or 'memberof')
		# Default: none
		#'attr_dom' => '',

		# Static domain to append to local parts (required, if 'mail_by' is 'static')
		# Default: none
		#'domain_static' => '',

		# Users with one of the following domains will be ignored (optional)
		# Default: none
		#'ignore_domains' => array(),

		# How to handle non-domain attributes in a DN (optional)
		# Set to 'stop', if you want to stop the search (e.g. uid=u1,dc=mail,dc=de,ou=dom,dc=example,dc=com --> mail.de)
		# Set to 'stop', if you want to skip non-domain attributes (e.g. uid=u1,dc=mail,dc=de,ou=dom,dc=example,dc=com --> mail.de.example.com)
		# Possible values: 'stop', 'skip'
		# Default: 'stop'
		#'non_domain_attr' => 'stop',

		### The following attributes can be fetched from LDAP in order to provide additional identity information

		# LDAP name attribute (optional)
		# Default: none
		#'attr_name' => '',

		# LDAP organization attribute (optional)
		# Default: none
		#'attr_org' => '',

		# LDAP reply-to attribute (optional)
		# Default: none
		#'attr_reply' => '',

		# LDAP bcc (blind carbon copy) attribute (optional)
		# Default: none
		#'attr_bcc' => '',

		# LDAP signature attribute (optional)
		# Default: none
		#'attr_sig' => '',
	),

	# 'alias_search' holds all config variables for the alias search
	'alias_search' => array(
		# LDAP search base (required)
		# - Use '%login' as a place holder for the login name
		# - Use '%local' as a place holder for the login name local part
		# - Use '%domain' as a place holder for the login name domain part (/'search_domain', if not given or replaced)
		# - Use '%email' as a place holder for the email address ('%local'@'%domain')
		# - Use '%dn' as a place holder for the DN returned by the user search
		# - Use '%%' as a place holder for '%'
		'base_dn' => 'ou=aliases,dc=example,dc=com',

		# LDAP search filter (optional)
		# This open filter possibility is the heart of the LDAP search.
		# - Use '%login' as a place holder for the login name
		# - Use '%local' as a place holder for the login name local part
		# - Use '%domain' as a place holder for the login name domain part (/'search_domain', if not given or replaced)
		# - Use '%email' as a place holder for the email address ('%local'@'%domain')
		# - Use '%dn' as a place holder for the DN returned by the user search
		# - Use '%%' as a place holder for '%'
		# Default: '(objectClass=*)'
		#'filter' => '(objectClass=*)',

		# LDAP alias derefencing (optional)
		# Possible values: never, search, find, always
		# Default: 'never'
		#'deref' => 'never',

		# How to find the e-mail addresses (required)
		# Possible values are:
		# - 'attribute' - e-mail address will be taken from the entry's 'attr_mail' attribute
		# - 'dn'        - e-mail address local part will be taken from the entry's 'attr_local';
		#                 e-mail address domain part will be taken from the DN's 'attr_dom' attributes
		# - 'memberof'  - e-mail address local part will be taken form the entry's 'attr_local';
		#                 e-mail address domain part will be taken from the memberOf attributes' 'attr_dom' attributes
		# - 'static'    - e-mail address local part will be taken form the entry's 'attr_local';
		#                 e-mail address domain part will be copied from 'domain_static'
		'mail_by' => 'attribute',

		# LDAP e-mail attribute (required, if 'mail_by' is 'attribute')
		'attr_mail' => 'mail',

		# LDAP e-mail local part attribute (required, if 'mail_by' is 'dn', 'memberof' or 'static')
		# Default: none
		#'attr_local' => '',

		# LDAP e-mail domain part attribute (required, if 'mail_by' is 'dn' or 'memberof')
		# Default: none
		#'attr_dom' => '',

		# Static domain to append to local parts (required, if 'mail_by' is 'static')
		# Default: none
		#'domain_static' => '',

		# Users with one of the following domains will be ignored (optional)
		# Default: none
		#'ignore_domains' => array(),

		# How to handle non-domain attributes in a DN (optional)
		# Set to 'stop', if you want to stop the search (e.g. uid=u1,dc=mail,dc=de,ou=dom,dc=example,dc=com --> mail.de)
		# Set to 'skip', if you want to skip non-domain attributes (e.g. uid=u1,dc=mail,dc=de,ou=dom,dc=example,dc=com --> mail.de.example.com)
		# Possible values: 'stop', 'skip'
		# Default: 'stop'
		#'non_domain_attr' => 'stop',

		### The following attributes can be fetched from LDAP in order to provide additional identity information

		# LDAP name attribute (optional)
		# Default: none
		#'attr_name' => '',

		# LDAP organization attribute (optional)
		# Default: none
		#'attr_org' => '',

		# LDAP reply-to attribute (optional)
		# Default: none
		#'attr_reply' => '',

		# LDAP bcc (blind carbon copy) attribute (optional)
		# Default: none
		#'attr_bcc' => '',

		# LDAP signature attribute (optional)
		# Default: none
		#'attr_sig' => '',
	),

	'general' => array(
		# Log level (optional)
		# Set the level of log details to be logged by this plugin
		# Possible values: 'error', 'warn', 'info', 'debug'
		# Default: 'error'
		#'log_level' => 'error',

		# Update identity (optional)
		# Set to true, if you want update an existing identity with the same e-mail address in the database
		# Possible values: true, false
		# Default: false
		#'update_existing' => false,

		# Update empty fields of the identity (optional)
		# Set to true, if you want to also update empty fields of the identity.
		# Possible values: true, false
		# Default: false
		#'update_empty_fields' => false,
	),
);
?>
