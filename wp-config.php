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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'c782c1c3' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '8huJbw273Ysk3' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

define('FS_METHOD' , 'direct');

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
define( 'AUTH_KEY',          'G^Qx4T-}X._ZLH!Q]fQnQwXV`6:M;Ic-Xhih6A`xcKqy]v}]&XROs&0#J%XM9Y} ' );
define( 'SECURE_AUTH_KEY',   ';5C_V(n0n)%BP|-=8*D#E&.aV&i$S Ayo{C9lRUP;dWSmR|S@)-t&!0J&z^/^N$x' );
define( 'LOGGED_IN_KEY',     '+.Yc2,i5hQpA0!UWZ$g#d2ueM@+I$2WJqo, bt14@avG;OOP+9wVUry?KO3{xn4d' );
define( 'NONCE_KEY',         'h>.w`E1I4jIk%39EER^iXZ=X.COU0rEI[!A2t2^@<w%K$Ur{h`P|dSq9/<`!~A]K' );
define( 'AUTH_SALT',         'LloTJn9JBKDU>R,*72hCC|,]VJS$:RA,+`oybd|>|8Id2C[o]xyiHUi+7p1J_t+H' );
define( 'SECURE_AUTH_SALT',  'c@4r>6#7EKgh&@=m/PtUkcZI#mPhOlnuzw:lMHHQb+*8v6LG=S*]==n?K--?z)TX' );
define( 'LOGGED_IN_SALT',    'Xz t$>$T69V3TImVnH)^4gB@_/p=/&=o[@]}SI$$/9FJf?%:t_z@dohCf?P8$!jB' );
define( 'NONCE_SALT',        'M|^k=Kil?ag!LmORTO%jGv,hsC@xa./%0WE!4a1T2v1uO`bN.V1,UZ1~kly.)O2)' );
define( 'WP_CACHE_KEY_SALT', 'omRz-5QDciqkt.FMpDq}zHnL]>Tu5@p|6/~hZs`jASb5u&$v>E/PZ$Q=9$YY8,J~' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

