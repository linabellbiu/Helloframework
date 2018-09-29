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
    static public $route = [];

    static public function get($name = '',$dir = '')
    {
        self::regRoute('get');
    }

    static public function post($name = '',$dir = '')
    {
        self::regRoute('post');
    }

    static public function request($name = '',$dir = '')
    {
        self::regRoute('request');
    }

    static public function regRoute($method = 'GET', $name, $byname = '')
    {
        if (strtolower($method) !=  strtolower(REQUEST_METHOD))
        {
            return null;
        }
        return Core::req();
    }

}