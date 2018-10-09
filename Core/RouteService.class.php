<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/9/29
 * Time: 14:28
 */
namespace Core;

class RouteService
{
    /**
     * 绑定的路由
     * @var array
     */
    static public $route = [];

    /**
     * 绑定的参数
     * @var array
     */
    static public $validateData = [];

    /**
     * @param string $name
     * @param string $url
     * @param $arg
     * @return Validate
     */
    static public function get($name = '', $url = '', $arg)
    {
        self::regRoute('GET', $name, $url);
        return self::bindingParam($arg);
    }

    /**
     * @param string $name
     * @param string $url
     * @param $arg
     * @return Validate
     */
    static public function post($name = '', $url = '', $arg)
    {
        self::regRoute('POST', $name, $url);
        return self::bindingParam($arg);
    }

    /**
     * @param string $name
     * @param string $url
     * @param $arg
     * @return Validate
     */
    static public function request($name = '', $url = '', $arg)
    {
        self::regRoute('REQUEST', $name, $url);
        return self::bindingParam($arg);
    }

    /**
     * 注册路由
     * @param $method
     * @param $name
     * @param $url
     */
    static private function regRoute($method, $name, $url)
    {
        if (empty($name)) {
            $name = INDEX_CONTROLLER . CONTROLLER_METHOD_DELIMIT . INDEX_METHOD;
            $url = '/index';
        }
        try {
            if (!is_string($name) && !is_string($url)) {
                throw new Error('路由格式错误');
            }
            if (strpos($name, CONTROLLER_METHOD_DELIMIT)) {
                throw new Error('路由  ' . CONTROLLER_METHOD_DELIMIT . ' 找不到');
            }
        } catch (Error $e) {
            $e->getMessage();
        }
        self::$route[strtoupper($method)][uconlyfirst($url)] = $name;
    }

    /**
     * @param $arg
     * @return Validate
     */
    static public function bindingParam($arg)
    {
        return Validate::getinstance()->rulesData($arg);
    }
}