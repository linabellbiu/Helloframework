<?php




// URL 模式定义
const URL_COMMON   = 0; //普通模式
const URL_PATHINFO = 1; //PATHINFO模式
const URL_REWRITE  = 2; //REWRITE模式

// 类文件后缀
const EXT = '.class.php';











require_once PATH."/vendor/autoload.php";
\Core\Core::start();