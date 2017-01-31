<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'lovelimo_wp1');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '7VfNoejtAlEP0aHDClxIBErUQZZXfjE33NNJNF5GcjqF0grZBxp1E6uOrYeTpYAc');
define('SECURE_AUTH_KEY',  'K1Aaf2lsPGYrxt7yKHbUAYNUCg8NaIAfeKiTXc1nF2QY3GDyqEp5QekvbTptYWsj');
define('LOGGED_IN_KEY',    'Nlh22SexzSVdk2mc1NxdGLd5Emhv4HGSzasTNgzp1PbRzHK4flbdlNjXXJ6EnTsR');
define('NONCE_KEY',        '7B8v2x0arKZ1yTHM5neZcbz94oHEKOkvF3Y96YOM8NfNu4fcjjSzdyk7ufSQ276E');
define('AUTH_SALT',        'MEHyUzNcMaTqKPtlUgnEzUqRjTfaFpx6XYXwPgITMSSAcApiOz6zPfzU9g9MgNv5');
define('SECURE_AUTH_SALT', 'mGdmFhZ2Wfrfn56hpofA21B3pXI6TJ9eitzhrKxMS8dLpVqFrgzVTBUEfT9YWaL3');
define('LOGGED_IN_SALT',   '6OtU5egG0woSb88VSexub7VsVz7FKsYkEqPVfkJ4UciBmyhPAC5Z4JgGOgtHfQ3S');
define('NONCE_SALT',       '5JtQHs56a46QzSCDZ8NkAUGXcXSevwRWRXPxh98Zqu4wspsdvPlhm7k476QFCAEG');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);

define('WP_HOME','http://wp.local/');
define('WP_SITEURL','http://wp.local/');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
