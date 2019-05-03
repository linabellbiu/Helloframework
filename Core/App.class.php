<?php

namespace Core;

use View\Compiled\AexCompiler;
use View\Factory;
use View\FileViewFinder;
use View\Engine\CompilerEngine;
use View\Support\Filesystem;
use View\Compiled;
use View\View;

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
        include_once CORE_COMMON_PATH . 'function.php';

        //加载系统配置文件
        config(load_file(SYS_CONFIG_PATH));

        //加载公共函数

        try {
            //加载路由
            if (file_exists(APP_ROUTE)) {
                require APP_ROUTE;
            } else {
                throw new Error('route.php 在' . APP_ROUTE . ' 
                没有找到');
            }

            if (file_exists(APP_INTI)) {
                foreach (include APP_INTI as $k => $v) {
                    //加载应用配置
                    if ($k == APP_CONFIG) {
                        foreach ($v as $app_config) {
                            config(load_file(APP_CONFIG_PATH . $app_config . CONFIG));
                        }
                    }
                    //加载语言包
                    if ($k == APP_LANGUAGE) {
                        //加载语言包
                        Custom::setCustom(load_file(APP_LANGUAGE_PATH . config('language') . '.php'));
                    }
                }
            } else {
                throw new Error('init.php 在', APP_PATH . ' 没有找到');
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
        $web_dir = '';
        try {
            if ($n) {
                $request_url = trim(substr($request_url, 0, $n), '/');
            }
            foreach (RouteService::$route[REQUEST_METHOD] as $url => $control) {
                if (trim($request_url, '/') == trim($url, '/')) {
                    $args = explode(CONTROLLER_METHOD_DELIMIT, $control);
                    if (empty($args[1])) {
                        throw new Error('找不到' . CONTROLLER_METHOD_DELIMIT);
                    } else {
                        $web = $args[0];
                        $m = $args[1];
                        if (strpos($web, '/')) {
                            list($web_dir, $c) = explode('/', $web);
                        }
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
            if (!empty($web_dir)) {
                if (!only_english_letter($web_dir)) {
                    throw new Error("目录名名不合法,必须全部是英文字母");
                }
            }
        } catch (Error $e) {
            echo $e->errorMessage();
        }
        define("__WEB_DIR__", $web_dir);
        define("__M__", $m);
        define("__C__", $c);
    }

    /**
     * 获取控制器
     */
    public static function getController()
    {
        $class = __C__;
        $web_dir = '';
        if (__WEB_DIR__ != '') {
            $controllerDir = APP_PATH . 'controller/' . __WEB_DIR__ . '/' . $class . EXT;
            define("__CLASSEXPLAME__", __CONTEROLLERINFO__ . __WEB_DIR__ . '\\' . $class);
        } else {
            $controllerDir = APP_PATH . 'controller/' . $class . EXT;
            define("__CLASSEXPLAME__", __CONTEROLLERINFO__ . $class);
        }
        define("__CONTROLLERDIR__", $controllerDir);
    }

    /**
     * 执行应用程序
     */
    public static function exec()
    {
        Core::listen();

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

    public static function template_load()
    {
        //绑定模板系统文件助手
        Factory::bind('Filesystem', function () {
            return new Filesystem();
        });

        //绑定编译器
        Factory::bind('AexCompiler', function () {
            return new AexCompiler(Factory::make('Filesystem'), config('templet_cache_path'));
        });

        //绑定编译引擎
        Factory::bind('CompilerEngine', function () {
            return new CompilerEngine(Factory::make('AexCompiler'));
        });

        //绑定模板文件阅读器
        Factory::bind('FileViewFinder', function () {
            $FileViewFinder = new FileViewFinder(Factory::make('CompilerEngine'), Factory::make('AexCompiler'));
            $FileViewFinder->path = config('template_path');
            return $FileViewFinder;
        });

        //绑定模板工厂
        Factory::bind('Factory', function () {
            return new Factory(Factory::make('FileViewFinder'));
        });

        //绑定视图
        Factory::bind('View', function () {
            return new View(Factory::make('Factory'), Factory::make('CompilerEngine'));
        });
    }
}