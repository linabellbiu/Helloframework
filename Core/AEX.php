<?php
// URL 模式定义
const URL_COMMON   = 0; //普通模式
const URL_PATHINFO = 1; //PATHINFO模式
const URL_REWRITE  = 2; //REWRITE模式

// 类文件后缀
const EXT = '.class.php';

define("UTF_8",true);
// 定义应用目录
define('APP_PATH', PATH.'/'.APP_NAME.'/');
define("__CONTEROLLERINFO__","\\".APP_NAME."\\controller\\");

if (UTF_8)
{
    header("Content-type:text/html;charset=utf-8");
}

//var_dump($_SERVER);exit;

require_once PATH."/vendor/autoload.php";
\Core\Core::run();