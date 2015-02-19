<?php

if (preg_match_all('#\b(.free|.dev|.xip.io|dev.)\b#', $_SERVER['HTTP_HOST'], $matches)){
    define('ENV', 'dev');
}
else {
    define('ENV', 'prod');
}

define('MULTILINGUAL', false);
define('MULTILINGUAL_EXTENDED', false);     // When we need to extend to a country
define('DEFAULT_LANGUAGE', 'en');

define('TABLET_SUPPORT', true);
define('MOBILE_SUPPORT', false);
	
define('DOMAIN', 'base');
define('ROOT_WEB', 'http://dev.' . DOMAIN);
define('SERVICES', 'http://services.' . DOMAIN);
define('MEDIAS', 'http://medias.' . DOMAIN);
define('UPLOADS', 'http://uploads.' . DOMAIN);