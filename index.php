<?php
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
define('APP_DEBUG',true);
define('APP_PATH','./App/');

/*if(!is_file(APP_PATH . 'Common/Conf/install.lock')){
	header('Location: ./install.php');
	exit;
}*/

define('RUNTIME_PATH','./Runtime/');
require './ThinkPHP/ThinkPHP.php';