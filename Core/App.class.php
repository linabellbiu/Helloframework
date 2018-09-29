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

        if (UTF_8) {
            header("Content-type:text/html;charset=utf-8");
        }

        //加载公共函数
        include_once CORE_COMMON_PATH . 'function.php';

        //加载配置文件
        config(load_config(SYS_CONFIG_PATH));

        try {
            if (file_exists(APP_INTI)) {
                foreach (include APP_INTI as $k => $v) {
                    if ($k == APP_CONFIG) {
                        foreach ($v as $app_config) {
                            config(load_config(APP_PATH . 'conf/' . $app_config . CONFIG));
                        }
                    }
                }
            } else {
                throw new Error('init.php 在', APP_PATH . ' 没有找到');
            }
        } catch (Error $e) {
            echo $e->getMessage();
        }
    }

    public static function dispatch()
    {
        self::urlMapp();
        self::getController();
    }

    /**
     * url 映射
     */
    public static function urlMapp()
    {
        $c = INDEX_CONTROLLER;
        $m = INDEX_METHOD;

        $n = strpos(REQUEST_URI,'?');
        $u = substr(REQUEST_URI,0,$n);
        $res = preg_match('/' . MAIN_FILE . '/i', $u, $matches);
        if ($res) {
            $args = explode(MAIN_FILE, $u);
        } else {
            $args = explode('/', $u);
        }

        if($args[1] !== '?') {
            if (!empty($args[1])) {
                $c = uconlyfirst($args[1]);
            }
            if (!empty($args[2])) {
                $m = strtolower($args[2]);
            }
        }

        try {
            if (!only_english_letter($c)) {
                throw new Error("控制器名不合法,必须全部是英文字母");
            }
            if (!only_english_letter($m)) {
                throw new Error("方法名不合法,必须全部是英文字母");
            }

        } catch (Error $e) {
            echo $e->errorMessage();
        }

        if(!route($c, $m))
        {
            $c = INDEX_CONTROLLER;
            $m = INDEX_METHOD;
        }
        define("__M__", $m);
        define("__C__", $c);
    }

    /**
     * 获取控制器
     */
    public static function getController()
    {
        $class = __C__ . 'Controller';
        $controllerDir = APP_PATH . 'controller/' . $class . EXT;
        define("__CONTROLLERDIR__", $controllerDir);
        define("__CLASSEXPLAME__", __CONTEROLLERINFO__ . $class);
    }

    /**
     * 执行应用程序
     */
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