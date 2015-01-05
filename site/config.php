<?php

if (preg_match_all('#\b(.free|.dev|.xip.io|dev.)\b#', $_SERVER['HTTP_HOST'], $matches)){
    define('ENV', 'dev');
}
else {
    define('ENV', 'prod');
}

define('MULTILINGUAL', true);
define('MULTILINGUAL_EXTENDED', false);     // When we need to extend to a country
define('DEFAULT_LANGUAGE', 'en');

define('TABLET_SUPPORT', true);
define('MOBILE_SUPPORT', false);
	
define('DOMAIN', 'base');
define('ROOT_WEB', 'http://dev.' . DOMAIN . '/');
define('SERVICES', 'http://services.' . DOMAIN);
define('MEDIAS', 'http://medias.' . DOMAIN);
define('UPLOADS', 'http://uploads.' . DOMAIN);

define('ROOT_WEB', '/');

define('CSS', ROOT_WEB . 'css/');
define('JS', ROOT_WEB . 'js/');
define('IMG', ROOT_WEB . 'css/');
define('MEDIAS', ROOT_WEB . 'medias/');
define('FAVICONS', ROOT_WEB . 'favicons/');