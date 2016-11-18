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
define('DB_NAME', 'cruxstore');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '^a$/Q|/R6*0}jrPa w9f/gy<t^?&Lm++,v0{.N(6Rk[0-wU:Zd`B<fd+cme_pd9u');
define('SECURE_AUTH_KEY',  'hRjlrc[f.@[7{NG{+6P;,AjygvA5a&bWoq?S9,&!-qr^DU >9 qt% A[HFitHC5!');
define('LOGGED_IN_KEY',    'b-ZV`|Ms~YK_)e4PD`FwZccM&L2z90K~Kzk?qqS_W4.<)BGO84n@x6AT,5uwt77=');
define('NONCE_KEY',        'MqH)hnXbnrZLihASTe,0JnHftr9hS(Qp,M%`NlV9B]s@+4o+,.s>-vHOguR9R=GK');
define('AUTH_SALT',        'Yc6*N;]_KZG6;r|rM~^K`l8OO+vJ-4V;OsSqc+.USD2nVj|qT(^:[yF*:NC[JSQ+');
define('SECURE_AUTH_SALT', '$OaUDI0+r$L4M`G_0-kwGudwAW>d!$*/=u.WPN|1&FLv[H0|Gu-4~4JrDX~A5<{.');
define('LOGGED_IN_SALT',   '2<t*LR.<>p<?OM1);NZ+G3N;le_>[$b?U*pQD;@`TWbfqfGs-h }T?3f-0?poD~b');
define('NONCE_SALT',       'TE&M_?`^P+1-e{o>ngaz^p$42jQFE0/m|#FhEAcTPAeqBOnS>E7.jPt5N9APX- V');

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
