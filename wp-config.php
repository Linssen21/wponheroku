<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
// define( 'DB_NAME', 'swoop' );

// /** MySQL database username */
// define( 'DB_USER', 'root' );

// /** MySQL database password */
// define( 'DB_PASSWORD', '' );

// /** MySQL hostname */
// define( 'DB_HOST', 'localhost' );

// /** Database charset to use in creating database tables. */
// define( 'DB_CHARSET', 'utf8mb4' );

// /** The database collate type. Don't change this if in doubt. */
// define( 'DB_COLLATE', '' );

// define( 'DB_NAME', 'HjIcaugmKI' );

// /** MySQL database username */
// define( 'DB_USER', 'HjIcaugmKI' );

// /** MySQL database password */
// define( 'DB_PASSWORD', 'JeMMOFjPEE' );

// /** MySQL hostname */
// define( 'DB_HOST', 'remotemysql.com' );

// /** Database charset to use in creating database tables. */
// define( 'DB_CHARSET', 'utf8mb4' );

// /** The database collate type. Don't change this if in doubt. */
// define( 'DB_COLLATE', '' );



define( 'DB_NAME', 'epiz_30735154_wpswoopheroku' );

/** MySQL database username */
define( 'DB_USER', 'epiz_30735154' );

/** MySQL database password */
define( 'DB_PASSWORD', '9xrsS9vXK2H8D4' );

/** MySQL hostname */
define( 'DB_HOST', 'sql211.epizy.com' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );


/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '|>IUzEDq.Fq}bHcv~4AuHZz|h!#wRM2;@WCu{X/j L+D@ck#hrBW_2Svpm?/;!,%' );
define( 'SECURE_AUTH_KEY',  ')x%+!ARHCU}K{9l2CEN%Ufg_&tS`q{Xk&a# m@SX6fNNidVGWOKJ,:nH5zY^jLpc' );
define( 'LOGGED_IN_KEY',    '{Np@#.`;Idk[~H,8svH1iibMJ/Krn0O~_.ZDn&leID;w.BA5t3NknS]d}DCs;%?*' );
define( 'NONCE_KEY',        'MYEZ_gzkHd65cCI2d}K6jfx2_FQC{KOs)wib7IGTxVm(t/Qkk|CDRv}%eTNhUeRd' );
define( 'AUTH_SALT',        'J?Z1nzF(ZbB hDJsQPe&2[!|$rMOh;Lp98%I{bB4SVfbC3q{&8RA_i1-cEK&hp?7' );
define( 'SECURE_AUTH_SALT', 't=j@]Nc|, /}l#*XQ#>}Rj-XWkGM)^w>/>q5tB.LuxkrpFCA}gl.=j|yRi,Dg)7L' );
define( 'LOGGED_IN_SALT',   'Q(_,}TvcEP^|5@Uf{5hasG7jj69E 1c 1y}{!Ma[U);T)#o+(W*Q<V=E$oHaRZ&r' );
define( 'NONCE_SALT',       '=$fHK+IZ}]@!>8BYhNPAA3j]{@x*}!n}baVOYkoqz[&<D7Z|y>zG#-B:X~zLvnvx' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
