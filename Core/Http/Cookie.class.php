<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/10/11
 * Time: 10:53
 */

namespace Core\Http;

class Cookie
{

    static public function getCookie($name)
    {
        if (is_string($name)) {
            return $_COOKIE[$name];
        }

        $c = [];
        if (is_array($name) && !empty($name)) {
            foreach ($name as $k) {
                $c[$k] = $_COOKIE[$k];
            }
            return $c;
        }
        return null;
    }

    static public function setCookie($name, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null)
    {
        if (is_string($name) && is_string($value)) {
            setcookie($name, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null);
        }
    }

    static public function delete($name, $path = null)
    {
        if (is_string($name)) {
            setcookie($name, null, time() - 24 * 3600, $path = null);
        }
    }
}