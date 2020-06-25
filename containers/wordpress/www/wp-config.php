<?php
define( 'DB_NAME', isset($_ENV['WORDPRESS_DB_NAME'])? $_ENV['WORDPRESS_DB_NAME']: 'wordpress' );
define( 'DB_USER', isset($_ENV['WORDPRESS_DB_USER'])? $_ENV['WORDPRESS_DB_USER']: 'root' );
define( 'DB_PASSWORD', isset($_ENV['WORDPRESS_DB_PASSWORD'])? $_ENV['WORDPRESS_DB_PASSWORD']: 'root' );
define( 'DB_HOST', isset($_ENV['WORDPRESS_DB_HOST'])? $_ENV['WORDPRESS_DB_HOST']: 'localhost' );
define( 'DB_CHARSET', isset($_ENV['WORDPRESS_DB_CHARSET'])? $_ENV['WORDPRESS_DB_CHARSET']: 'utf8mb4' );
define( 'DB_COLLATE', isset($_ENV['WORDPRESS_DB_COLLATE'])? $_ENV['WORDPRESS_DB_COLLATE']: '' );

define('AUTH_KEY',         '7 `<5A;P)jtWF%btfJAOO1Md:R|Wz7Me#s;U65spELD?i0yH+cBi-a].zSb.5r)M');
define('SECURE_AUTH_KEY',  'rI9l$zJI}}q(~8`jDy{g9=^05Yj+.4JFW2}@VE_G,=z3W?|9c3`xyBJTfjHK6O3R');
define('LOGGED_IN_KEY',    'g|H+&jU!c0n4MoHv^Y6j|m9+[;aW31N:K> ecToA#=$!7UI`5U%~s!A^Trlz{= 7');
define('NONCE_KEY',        'Axq@H&8Fm(cq)s1{rDr]CNa8+nVy9YCGX)^K+4>n]@YxcFU9%-My(+%0#~?qCT%n');
define('AUTH_SALT',        '+I6a&f;~VJ(*ht_ mu4uQjz1uhrL7~mb|$M=^z1<Pt3qLV+iU/aNDU;9FNtGP~Hg');
define('SECURE_AUTH_SALT', 'A^ |?tdmO=q.0.Z}*-=(:r*oj2W[[8Gv=`Y-s_5|35JL-L0XT|r(k#&>h1o#&vzD');
define('LOGGED_IN_SALT',   '&w/Ygay)Y%>j+8v&7NqEK@eb$3yLauP|Ho#X+(pmNuL8l9|7}64lO|uX n>@to-I');
define('NONCE_SALT',       'AD`IQ:N=938glM}-#LF/Mirk4U!T3^Dp+bS995EU%Iq*mAQ>T>&3z_!nBE!2bbhu');

$table_prefix = isset($_ENV['WORDPRESS_DB_PREFIX'])? $_ENV['WORDPRESS_DB_PREFIX']: 'wp_';

define( 'WP_DEBUG', isset($_ENV['WORDPRESS_DEBUG'])? $_ENV['WORDPRESS_DEBUG']: false );
define( 'WPLANG', 'ja' );

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

require_once( ABSPATH . 'wp-settings.php' );
