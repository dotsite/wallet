<?php
## Основные настройки сайта, в дальнейшем будут переписанны
define(_CHARSET_,'utf8');
if ($header == '') {
header("Content-Type: text/html; charset="._CHARSET_);
}
define(_base_url_,"http://".$_SERVER['HTTP_HOST']."/");
define(BASE_PATH,"http://".$_SERVER['HTTP_HOST']);

define(_BD_NAME_,'wallet');
define(_BD_USER_,'root');
define(_BD_PASS_,'');
define(_BD_SERVER_,'localhost');


define(_FULL_PATH_,dirname(dirname(__FILE__))."/");
define(BASE_DIR,dirname(dirname(__FILE__))."/");
define(_CACHE_PATH_,_FULL_PATH_.'cache');
define(_QU_,$q);
define(_CACHE_,0);
?>