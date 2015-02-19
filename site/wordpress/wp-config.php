<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur 
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'wordpress.base');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'root');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8');

/** Type de collation de la base de données. 
  * N'y touchez que si vous savez ce que vous faites. 
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant 
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '7fOPC%fHdkt/NnX n:3+6|m{^(^[x[#m8rxD6r*ZR Qc,S[j,FyZ8] *y9<WlNMn');
define('SECURE_AUTH_KEY',  '9,lE>uM-)-[5Rl[:d%{fXEFdR~r18sC`7GYI49gMUt.wjfnb@G6##mIdqTjoNh(4');
define('LOGGED_IN_KEY',    '_Z;+mtjV@wy?@I%^A+U/?gXp*iszro[|m3|ekqH1!Z}Hc),`Lsd+83VIV*JB1}EP');
define('NONCE_KEY',        '_a0@b/QkuFv2*fKJ|A>.-Lmd7eqvs((YtDD_lP<.3l!1s3>2i-gBlA_= mD.(-dW');
define('AUTH_SALT',        '66*xj6,1d+]zPZ@:TfDzx*J} !}<n0:[2-uA?:{X(^SBh|Cq(WP-Psmg|0&Xb{z6');
define('SECURE_AUTH_SALT', '8.c0_e(|saA*%W9lv+qo@2Gjq{z_ n!fQeU@zmL:9]mB?Q-LLB{QK{t)fdq.<uz2');
define('LOGGED_IN_SALT',   '_Y:OYFAP+2wA3xhS6uU}I3C0{7qqgS,{GpMc0x?-^|6N`q+@7|6?whcw Ex3Z9;C');
define('NONCE_SALT',       '*Bhd]rOQH P7?j/_I9&7nXO)*cu0r-,<b/]F:dX)v|-P[O2;|G^^X0j9p*/V}<!Q');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique. 
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'bb_';

/** 
 * Pour les développeurs : le mode deboguage de WordPress.
 * 
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant votre essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de 
 * développement.
 */ 
define('WP_DEBUG', false); 

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');