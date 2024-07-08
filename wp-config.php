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
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'autolisting2' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         'nX4Cfm{AWo0rgI|uV0pBwm[g!UuJ}CZ9yAC<z2PQl/qq:d7N%u5mM7O&z+>rc>&s' );
define( 'SECURE_AUTH_KEY',  '$:^s%pCPtFvN#]rQ*W .}9nwN1q1%w#UC7W5CgzTiU< bH z~sA`H-:[UmeZBC,-' );
define( 'LOGGED_IN_KEY',    ';yjZ2cuwe?l;LL%|cDtE/VUn[F~9~j_Z2U+?FJ-FiNluUx)Z!Pt(vmx/^$Y:fR6h' );
define( 'NONCE_KEY',        'IR&8N%:cx-O1D&sw0+xy*w]`@Y~_9&~#LY1ct#L]L^7Urp,^nV57dWmphg,4^O/W' );
define( 'AUTH_SALT',        '9~6Y7K/$CZipjw<`t%fo.[HkhHh)d8^#SN5OX11}Oc!uGE(b1gM($Y,z./b$@4pj' );
define( 'SECURE_AUTH_SALT', '8P]Y/XL|S;ogGfPKLZzUZD4QQ(b*1F?`g|D,A/JKVr&s{V&KSXh)3l=,imf[Gejk' );
define( 'LOGGED_IN_SALT',   '/aIot$[HoL:|Cj3(S6Pb88i5dEFW&B9,PG|^vVl.}>}Po/ahh@u~[0]ZQ 9={=j{' );
define( 'NONCE_SALT',       'wu</]$& fmz]|[=%G _i^.$:#u8y,^$]NFx{Q{e?%OJcP0Q:tSn-;yk-uq$&cVul' );

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
