<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/10/11
 * Time: 10:53
 */

namespace Core\Http;


use Core\Validate;
class Cookie
{

    static private $cookie = [];

    static public function cookie($name = null, $value = null)
    {
        if (empty($value)) return self::getCookie($name);
        if (!empty($name) && !empty($value)) return self::setCookie($name,$value);
        return null;
    }

    static private function getCookie($name = null)
    {
        if (empty($name)) {
            return empty(self::$cookie) ? false : self::$cookie;
        }

        if (is_string($name)){
            if(empty(self::$cookie[$name]))
            {
                if(Validate::$instance->safe([$name=>Validate::$instance->validateCookie[$name]], [$name =>$_COOKIE[$name]]))
                {
                    self::$cookie[$name] = $_COOKIE[$name];
                    return self::$cookie[$name];
                }else
                {
                    return false;
                }
            } else {
                self::$cookie[$name];
            }
        }
        return false;
    }


    static private function setCookie($name, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null)
    {
        if (is_string($name) && is_string($value)) {
            setcookie($name, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null);
            return true;
        }
        return false;
    }

    static public function delete($name, $path = null)
    {
        if (is_string($name)) {
            if(setcookie($name, null, time() - 24 * 3600, $path = null))
            {
                return true;
            }
        }
        return false;
    }
}