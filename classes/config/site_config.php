<?php
## Основные настройки сайта, в дальнейшем будут переписанны
define(_CHARSET_,'utf8');
if ($header == '') {
header("Content-Type: text/html; charset="._CHARSET_);
}
define(_base_url_,"http://".$_SERVER['HTTP_HOST']."/");
define(BASE_PATH,"http://".$_SERVER['HTTP_HOST']);

define(_BD_NAME_,'ld24');
define(_BD_USER_,'root');
define(_BD_PASS_,'');
define(_BD_SERVER_,'localhost');
/*
$server_name = "localhost";
$login = "stengazzetta";
$password = "12wedfcvzxdf";
$base_name = "stengazzetta";
//$base_name = "dev_new_sg";
if (!defined('BASE_DIR'))
    define('BASE_DIR', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
*/

/* define(_BD_NAME_,'wt1000896_20');
define(_BD_USER_,'wt1000896_20');
define(_BD_PASS_,'HcegABIW');
*/
//define(_FULL_PATH_,'/usr/home/hosting/wt1000896/htdocs20/');
define(_FULL_PATH_,dirname(dirname(__FILE__))."/");
define(BASE_DIR,dirname(dirname(__FILE__))."/");
define(_CACHE_PATH_,_FULL_PATH_.'cache');
define(_url_prefix_,"");
define(_base_url_b,'http://fedobr.ru/');
define(_base_url_b2,'http://fedobr.ru');
define(_URL_,_base_url_._url_prefix_);
define(_SKINS_FOLDER_,_FULL_PATH_.'themes/');
define(_QU_,$q);
define(_CACHE_,0);
?>