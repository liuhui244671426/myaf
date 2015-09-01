<?php
/**
 * Created by PhpStorm.
 * User: liuhui
 * Date: 15/8/30
 * Time: 21:14
 */
header('content-type:text/html;charset=utf-8');
error_reporting(E_ALL);

define('MODE', 'dev');//运行环境
date_default_timezone_set('PRC');

if (phpversion() >= "5.3") {
    define("PUBLIC_PATH", __DIR__);
} else {
    define("PUBLIC_PATH", dirname(__FILE__));
}

define('APPLICATION_PATH', realpath(PUBLIC_PATH . '/../')); //app dir

define('ROOT_PATH', realpath(APPLICATION_PATH . '/../')); //root dir