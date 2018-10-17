<?php
/**
 * Created by PhpStorm.
 * User: wangxudong
 * Date: 2018/10/11
 * Time: 10:53
 */

namespace Core\Http;

use Core\Validate;

class Request
{

    private static $request;

    public static function request()
    {
        switch (REQUEST_METHOD) {
            case 'GET':
                return self::getReq($_GET);

            case 'POST':
                return self::getReq($_POST);

            case 'REQUEST':
                return self::getReq($_REQUEST);
            default:
                return null;
        }
    }

    /**
     * 销毁没有验证的请求数据
     * @return null
     */
    public static function delete($name = null)
    {
        if (empty($name)) {
            if (REQUEST_METHOD == 'GET') $_GET = null;
            if (REQUEST_METHOD == 'POST') $_POST = null;
            if (REQUEST_METHOD == 'REQUEST') $_REQUEST = null;
        }

        if (is_string($name)) {
            if (REQUEST_METHOD == 'GET') $_GET[$name] = null;
            if (REQUEST_METHOD == 'POST') $_POST[$name] = null;
            if (REQUEST_METHOD == 'REQUEST') $_REQUEST[$name] = null;
        }
        return null;
    }

    /**
     * @param $req
     * @return array|bool
     */
    private static function getReq($req)
    {
        if (!empty(self::$request)) {
            return self::$request;
        }

        if (!is_array($req)) {
            return null;
        }

        if (!Validate::getinstance()->safe(Validate::$instance->validateData, $req)) {
            return null;
        }
        self::$request = $req;
        return self::$request;
    }
}