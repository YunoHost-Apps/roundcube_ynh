<?php

/*
 +-----------------------------------------------------------------------+
 | Local configuration for the Roundcube Webmail installation.           |
 |                                                                       |
 | This is a sample configuration file only containing the minimum       |
 | setup required for a functional installation. Copy more options       |
 | from defaults.inc.php to this file to override the defaults.          |
 |                                                                       |
 | This file is part of the Roundcube Webmail client                     |
 | Copyright (C) The Roundcube Dev Team                                  |
 |                                                                       |
 | Licensed under the GNU General Public License version 3 or            |
 | any later version with exceptions for skins & plugins.                |
 | See the README file for a full license statement.                     |
 +-----------------------------------------------------------------------+
*/

// Retrieve YunoHost main domain
$main_domain = exec('cat /etc/yunohost/current_host');

$config = array();

// Database connection string (DSN) for read+write operations
// Format (compatible with PEAR MDB2): db_provider://user:password@host/database
// Currently supported db_providers: mysql, pgsql, sqlite, mssql, sqlsrv, oracle
// For examples see http://pear.php.net/manual/en/package.database.mdb2.intro-dsn.php
// NOTE: for SQLite use absolute path (Linux): 'sqlite:////full/path/to/sqlite.db?mode=0646'
//       or (Windows): 'sqlite:///C:/full/path/to/sqlite.db'
$config['db_dsnw'] = 'mysql://__DB_NAME__:__DB_PWD__@localhost/__DB_NAME__';

// The IMAP host chosen to perform the log-in.
// Leave blank to show a textbox at login, give a list of hosts
// to display a pulldown menu or set one host as string.
// Enter hostname with prefix ssl:// to use Implicit TLS, or use
// prefix tls:// to use STARTTLS.
// Supported replacement variables:
// %n - hostname ($_SERVER['SERVER_NAME'])
// %t - hostname without the first part
// %d - domain (http hostname $_SERVER['HTTP_HOST'] without the first part)
// %s - domain name after the '@' from e-mail address provided at login screen
// For example %n = mail.domain.tld, %t = domain.tld
$config['default_host'] = 'localhost';

// SMTP server host (for sending mails).
// Enter hostname with prefix ssl:// to use Implicit TLS, or use
// prefix tls:// to use STARTTLS.
// Supported replacement variables:
// %h - user's IMAP hostname
// %n - hostname ($_SERVER['SERVER_NAME'])
// %t - hostname without the first part
// %d - domain (http hostname $_SERVER['HTTP_HOST'] without the first part)
// %z - IMAP domain (IMAP hostname without the first part)
// For example %n = mail.domain.tld, %t = domain.tld
// To specify different SMTP servers for different IMAP hosts provide an array
// of IMAP host (no prefix or port) and SMTP server e.g. ['imap.example.com' => 'smtp.example.net']
$config['smtp_server'] = 'tls://' . $main_domain;

// SMTP port. Use 25 for cleartext, 465 for Implicit TLS, or 587 for STARTTLS (default)
$config['smtp_port'] = 587;

// SMTP username (if required) if you use %u as the username Roundcube
// will use the current username for login
$config['smtp_user'] = '%u';

// SMTP password (if required) if you use %p as the password Roundcube
// will use the current user's password for login
$config['smtp_pass'] = '%p';

// SMTP socket context options
// See http://php.net/manual/en/context.ssl.php
// The server certificate validation is disabled, since the server is local
// and the communication should be safe. Note that it can be enabled as
// needed, just uncomment lines and set 'verify_peer' to true.
$config['smtp_conn_options'] = array(
  'ssl'         => array(
    'verify_peer'  => false,
//    'verify_depth' => 3,
//    'cafile'       => '/etc/yunohost/certs/' . $main_domain . '/ca.pem',
  ),
);

// provide an URL where a user can get support for this Roundcube installation
// PLEASE DO NOT LINK TO THE ROUNDCUBE.NET WEBSITE HERE!
$config['support_url'] = 'https://forum.yunohost.org/t/roundcube-a-webmail/3965';

// Name your service. This is displayed on the login screen and in the window title
$config['product_name'] = 'YunoHost Webmail';

// This key is used to encrypt the users imap password which is stored
// in the session record. For the default cipher method it must be
// exactly 24 characters long.
// YOUR KEY MUST BE DIFFERENT THAN THE SAMPLE VALUE FOR SECURITY REASONS
$config['des_key'] = '__DESKEY__';


// ----------------------------------
// USER INTERFACE
// ----------------------------------

// the default locale setting (leave empty for auto-detection)
// RFC1766 formatted language name like en_US, de_DE, de_CH, fr_FR, pt_BR
$config['language'] = '__LANGUAGE__';

// use this format for date display (date or strftime format)
$config['date_format'] = 'd-m-Y';

// Make use of the built-in spell checker. It is based on GoogieSpell.
$config['enable_spellcheck'] = false;


// Enable YunoHost users search in the address book.
$config['ldap_public']['yunohost'] = array(
  'name' => 'YunoHost Users',
  'hosts' => array('localhost'),
  'port' => 389,
  'user_specific' => false,
  'base_dn' => 'ou=users,dc=yunohost,dc=org',
  'scope' => 'list',
  'filter' => '(objectClass=mailAccount)',
  'hidden' => false,
  'searchonly' => true,
  'fieldmap' => array(
    'name'      => 'uid',
    'surname'   => 'sn',
    'firstname' => 'givenName',
    'email'     => 'mail:*',
  ),
);

// List of active plugins (in plugins/ directory)
$config['plugins'] = array(
    'archive',
    'zipdownload',
    // additionnal plugins
    'http_authentication',
    'managesieve',
    'markasjunk',
    'new_user_dialog',
    'new_user_identity',
    'enigma',
);

// ----------------------------------
// PLUGINS
// ----------------------------------

// -- new_user_identity
// The id of the address book to use to automatically set a
// user's full name in their new identity.
$config['new_user_identity_addressbook'] = 'yunohost';

// -- http_authentication
// Redirect the client to this URL after logout.
$config['logout_url'] = 'https://' . $main_domain . '/yunohost/sso/?action=logout';

// -- managesieve
// Enables separate management interface for vacation responses (out-of-office)
$config['managesieve_vacation'] = 1;

// -- ldapAliasSync
$config['ldapAliasSync'] = array(
  // Mail parameters
  'mail' => array(
    'dovecot_separator' => '+',
  ),
  // LDAP parameters
  'ldap' => array(
    'bind_dn' => '',
  ),
  # 'user_search' holds all config variables for the user search
  'user_search' => array(
    'base_dn'   => 'uid=%local,ou=users,dc=yunohost,dc=org',
    'filter'    => '(objectClass=mailAccount)',
    'mail_by'   => 'attribute',
    'attr_mail' => 'mail',
    'attr_name' => 'cn',
  ),
  # 'alias_search' holds all config variables for the alias search
  'alias_search' => array(
    'base_dn'   => 'uid=%local,ou=users,dc=yunohost,dc=org',
    'filter'    => '(objectClass=mailAccount)',
    'mail_by'   => 'attribute',
    'attr_mail' => 'mailalias',
    'attr_name' => 'cn',
  ),
);

// skin name: folder from skins/
$config['skin'] = 'elastic';
