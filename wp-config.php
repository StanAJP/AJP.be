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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'ajpbe' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Djl;]9m%/[|TU*f:.afME7TL;5SS],W/;yf)[C(gnBf:Cziu=4]{c6LP><#YT&_5' );
define( 'SECURE_AUTH_KEY',  'fYM@+9Bm!o5PnrwA,O)`ufNvyD@e[.o+=oLHaz:N#VBG^CTeIGF.$rg[F!ctQ[ZW' );
define( 'LOGGED_IN_KEY',    '.zmw&rgu$c*}cIy,Q}y~n7fKx~93*|:6yM uA*m*aP8ujdxp]#2Qwj}g%HH)&.Dj' );
define( 'NONCE_KEY',        'GbGLOg|8Y3Rb3XpZQQtN(4*{GvSCCDEz2ob%E620<w&GKph{a>J[1Go_Orh_`-7c' );
define( 'AUTH_SALT',        '~3xB/+)4F.,ZPPn))zL)>FS@p4bI^@JGqvu14hI$iDeU(^i)!nCFLys!;l_H5llL' );
define( 'SECURE_AUTH_SALT', ';@ ocFE@DgFvNz>q<ssec1K8IL 8|P/t<UvnZ-J1?Eys^ gg14+p.SE}I<=7O|O2' );
define( 'LOGGED_IN_SALT',   '8:{0Gd+8Md7Sv$Z8VAVuu3[S6JW&Gu|hy9wYDR>Po9X/C^Qv+{{(6Q<ZRO4E3{m_' );
define( 'NONCE_SALT',       '|oGa lzPMPA3Coyv`hA=iB)lI(g`6qWw_F~@7FG_x7<{Q^Q7#?Mw=Y:~.IdXEa`)' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
