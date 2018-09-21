<?php

if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    die('require PHP > 5.4.0 !');
}

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', true);

//定义框架根目录
define("PATH",__DIR__);

// 定义应用目录
define('APP_PATH', PATH.'/App/');



// 引入入口文    件
require_once PATH . "/Core/AEX.php";