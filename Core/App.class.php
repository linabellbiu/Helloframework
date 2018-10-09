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
        define('IS_REQUEST', REQUEST_METHOD == 'REQUEST' ? true : false);

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

            //加载路由
            if (file_exists(APP_ROUTE)) {
                require APP_ROUTE;
            } else {
                throw new Error('route.php 在' . APP_ROUTE . ' 
                没有找到');
            }
            //加载规则
            if (file_exists(APP_RULE)) {
                require APP_RULE;
            } else {
                throw new Error('rule.php 在' . APP_RULE . ' 
                没有找到');
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

        $request_url = trim(REQUEST_URI);
        $n = strpos(REQUEST_URI, '?');
        $c = 'emptygUSHJBNJXCKVUAMJsfdsaadfdff';
        $m = 'emptyASDUMVZPDEIFASDFpabnHUASHJB';
        try {
            if ($n) {
                $request_url = trim(substr($request_url, 0, $n),'/');
            }
            foreach (RouteService::$route[REQUEST_METHOD] as $url => $control) {
                if (trim($request_url,'/') == trim($url,'/')) {
                    $args = explode(CONTROLLER_METHOD_DELIMIT, $control);
                    if (empty($args[1])) {
                        throw new Error('找不到' . CONTROLLER_METHOD_DELIMIT);
                    } else {
                        $c = $args[0];
                        $m = $args[1];
                    }
                }
            }
            if (empty($c)) {
                throw new Error('控制器名是空的');
            }
            if (empty($m)) {
                throw new Error('方法名是空的');
            }

            if (!only_english_letter($c)) {
                throw new Error("控制器名不合法,必须全部是英文字母");
            }
            if (!only_english_letter($m)) {
                throw new Error("方法名不合法,必须全部是英文字母");
            }

        } catch (Error $e) {
            echo $e->errorMessage();
        }

        define("__M__", $m);
        define("__C__", $c);
    }

    /**
     * 获取控制器
     */
    public static function getController()
    {
        $class = __C__;
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