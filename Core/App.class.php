<?php

namespace Core;


use Core\Hello\Build\SystemBuild;
use function PHPSTORM_META\type;

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

        SystemBuild::run();

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
        $web_dir = '';
        try {

            if ($n) {
                $request_url = trim(substr($request_url, 0, $n), '/');
            }
            do {
                if (empty(RouteService::$route)) {
                    list($c, $m) = Heplers::originalUrlControllerAndMothed(REQUEST_URI);
                    break;
                }
                foreach (RouteService::$route[REQUEST_METHOD] as $url => $control) {
                    if (trim($request_url, '/') == trim($url, '/')) {
                        $args = explode(CONTROLLER_METHOD_DELIMIT, $control);
                        if (empty($args[1])) {
                            throw new Error('找不到' . CONTROLLER_METHOD_DELIMIT);
                        } else {
                            $c = $web = $args[0];
                            $m = $args[1];
                            if (strpos($web, '.')) {
                                list($web_dir, $c) = explode('.', $web);
                            }
                        }
                    }
                }
                break;
            } while (0);

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
            if (!empty($web_dir)) {
                if (!only_english_letter($web_dir)) {
                    throw new Error("目录名名不合法,必须全部是英文字母");
                }
            }
        } catch (Error $e) {
            echo $e->errorMessage();
        }
        define('APP_MODULE', $web_dir);
        define('__M__', $m);
        define('__C__', $c);
    }

    /**
     * 获取控制器
     */
    public static function getController()
    {
        $class = __C__;
        if (APP_MODULE != '') {
            $controllerDir = APP_PATH . 'controller/' . APP_MODULE . '/' . $class . EXT;
            define("CLASS_EXPLAME", CONTEROLLER_INFO . APP_MODULE . '\\' . $class);
        } else {
            $controllerDir = APP_PATH . 'controller/' . $class . EXT;
            define("CLASS_EXPLAME", CONTEROLLER_INFO . $class);
        }
        define("CONTROLLER_DIR", $controllerDir);
    }

    /**
     * 执行应用程序
     */
    public static function exec()
    {
        Core::listen();

        try {
            if (!file_exists(CONTROLLER_DIR)) {
                throw new Error(CONTROLLER_DIR . "文件找不到");
            }
            require_once CONTROLLER_DIR;
            if (!class_exists(CLASS_EXPLAME)) {
                throw new Error(CONTROLLER_DIR . " 类:" . CLASS_EXPLAME . "找不到");
            }
            $class = CLASS_EXPLAME;
            $explame = new $class();
            $method = __M__;
            if (!method_exists($explame, $method)) {
                throw new Error(CONTROLLER_DIR . " 在" . CLASS_EXPLAME . "类,找不到" . $method . "方法");
            }
            $explame->$method();
        } catch (Error $e) {
            echo $e->errorMessage();
        }
    }
}