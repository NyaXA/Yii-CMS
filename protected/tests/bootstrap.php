<?php

// change the following paths if necessary
$yiit=dirname(__FILE__).'/../libs/yii/yiit.php';
$config=dirname(__FILE__).'/../config/test.php';

require_once($yiit);
require_once(dirname(__FILE__).'/WebTestCase.php');

if (substr($_SERVER['DOCUMENT_ROOT'], -1) != '/')
{
    $_SERVER['DOCUMENT_ROOT'] = $_SERVER['DOCUMENT_ROOT'].'/';
}

define("PROTECTED_PATH", $_SERVER["DOCUMENT_ROOT"] . "protected/");
define("MODULES_PATH", $_SERVER["DOCUMENT_ROOT"] . "protected/modules/");
define("LIBRARY_PATH", PROTECTED_PATH . "libs/");

Yii::createWebApplication($config);
