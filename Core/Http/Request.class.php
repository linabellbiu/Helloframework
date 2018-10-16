<?php
/**
 * Created by PhpStorm.
 * User: wangxudong
 * Date: 2018/10/11
 * Time: 10:53
 */

namespace Core\Http;
class Request
{
    public static function req()
    {
        switch (REQUEST_METHOD) {
            case 'GET':
                return $_GET;

            case 'POST':
                return $_POST;

            case 'REQUEST':
                return $_REQUEST;
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

    public static function getReq($request)
    {

    }
}