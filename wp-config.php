<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en « wp-config.php » et remplir les
 * valeurs.
 *
 * Ce fichier contient les réglages de configuration suivants :
 *
 * Réglages MySQL
 * Préfixe de table
 * Clés secrètes
 * Langue utilisée
 * ABSPATH
 *
 * @link https://fr.wordpress.org/support/article/editing-wp-config-php/.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'Cafeine' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'root' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', '' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/**
 * Type de collation de la base de données.
 * N’y touchez que si vous savez ce que vous faites.
 */
define( 'DB_COLLATE', '' );

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clés secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'v/}_wgIk$31U||0XtGBC(.Q9d`0c)V[o>Q<BVJ@9pVko3}A7!dOjHOC@m+R!R4^@' );
define( 'SECURE_AUTH_KEY',  'dKV.r F3d$,;/-_3&kLe4Esk<^@FhoO*4:.Y3=:Pbp&D`6_I{c9Qb9gF17.,1?;g' );
define( 'LOGGED_IN_KEY',    '!Z!S2K{-62.5.P)T^xhHomm4& 6xyqgE.7;}ilL@<Xz,]ax]4#l^8zz5Nk[!U3vT' );
define( 'NONCE_KEY',        'cjWo{3}01sa9k8eHojH+n)f^FdEy_ vXp15f3qM})Du5FaAn!~PxqQ,N}@%zzS #' );
define( 'AUTH_SALT',        'tFk(*Eri2]63s,yvS*/y]i4N3d1cWS?FN}Oq[DE.b0BKS}YW.aZzm}o7fc?$[p|E' );
define( 'SECURE_AUTH_SALT', '2C_`TF1m3= SgRRl?PV gx.yg/|+>EHeFWZ5??h {x(zisbsRW&*=6VQgl>:?#W8' );
define( 'LOGGED_IN_SALT',   'r5RG{Tz-s,]sz,Zq{RRJK!rPYkoAbM~iW:u87poT<X4R,V!qU[tqx5LT$4WuQGt_' );
define( 'NONCE_SALT',       '2`9J{,{e=?p?HMNWmcBgbq/NxlqShec-n.K9s)0:cw?(4l%k6;SQuD4EAmKOQ,cX' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortement recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://fr.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( ! defined( 'ABSPATH' ) )
  define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once( ABSPATH . 'wp-settings.php' );
