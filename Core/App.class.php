<?php
namespace Core;

class App
{

    static public function init()
    {

        // 定义当前请求的系统常量
        define('NOW_TIME', $_SERVER['REQUEST_TIME']);
        define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);
        define('REQUEST_URI', $_SERVER['REQUEST_URI']);
        define('IS_GET', REQUEST_METHOD == 'GET' ? true : false);
        define('IS_POST', REQUEST_METHOD == 'POST' ? true : false);
        define('IS_PUT', REQUEST_METHOD == 'PUT' ? true : false);
        define('IS_DELETE', REQUEST_METHOD == 'DELETE' ? true : false);

        //加载配置项
        self::load();
    }

    static public function load()
    {
        //加载公共函数
        include_once PATH . '/Core/common/func.php';

        //加载应用函数
        $app_conf = APP_PATH . '/commom/conf/conf.php';

        if (file_exists($app_conf)) {
            include_once $app_conf;
        }
    }

    public static function dispatch()
    {
        $c = "Index";
        $m = "index";
        $res = preg_match('/' . MAIN_FILE . '/i', REQUEST_URI, $matches);
        if ($res) {
            $args = explode(MAIN_FILE, REQUEST_URI);
        } else {
            $args = explode('/', REQUEST_URI);
        }

        if (!empty($args[1])) {
            $c = ucfirst(strtolower($args[1]));
        }
        if (!empty($args[2])) {
            $m = strtolower($args[2]);
        }

        try {
            if (!only_english_letter($c)) {
                throw new Error("控制器名不合法,必须全部是英文字母");
            }
            if (!only_english_letter($m))
            {
                throw new Error("方法名不合法,必须全部是英文字母");
            }

        } catch (Error $e) {
            echo $e->errorMessage();
        }

        define("__M__", $m);
        define("__C__", $c);
        self::getController();
    }

    public static function getController()
    {
        $class = __C__ . 'Controller';
        $controllerDir = APP_PATH . 'controller/' . $class . EXT;
        define("__CONTROLLERDIR__", $controllerDir);
        define("__CLASSEXPLAME__", __CONTEROLLERINFO__ . $class);
    }

    public static function exec()
    {
        try {
            if (!file_exists(__CONTROLLERDIR__)) {
                throw new Error(__CONTROLLERDIR__ . "文件找不到");
            }
            require_once __CONTROLLERDIR__;
            if (!class_exists(__CLASSEXPLAME__)) {
                throw new Error(__CONTROLLERDIR__ . " 类:" . __CLASSEXPLAME__ . "找不到");
            }
            $class = __CLASSEXPLAME__;
            $explame = new $class();
            $method = __M__;
            if (!method_exists($explame, $method)) {
                throw new Error(__CONTROLLERDIR__ . " 在" . __CLASSEXPLAME__ . "类,找不到" . $method . "方法");
            }
            $explame->$method();
        } catch (Error $e) {
            echo $e->errorMessage();
        }
    }
}