<?php

if (substr($_SERVER['DOCUMENT_ROOT'], -1) != '/')
{
    $_SERVER['DOCUMENT_ROOT'] = $_SERVER['DOCUMENT_ROOT'].'/';
}

require_once $_SERVER['DOCUMENT_ROOT'] . 'protected/config/constants.php';

$yii = LIBRARY_PATH . 'yii/yii.php';

ini_set("display_errors", 1);
error_reporting(E_ALL);

date_default_timezone_set('Europe/Moscow');

require_once($yii);
require_once(LIBRARY_PATH.'functions/debug_functions.php');
require_once(LIBRARY_PATH.'functions/php_5_3_functions.php');

$session = new CHttpSession;
$session->open();

$config = PROTECTED_PATH . '/config/' . getenv("APP_ENV") . '.php';

Yii::createWebApplication($config)->run();

?>



