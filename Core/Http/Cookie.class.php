<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/10/11
 * Time: 10:53
 */

namespace Core\Http;


use Core\Error;
use Core\Validate;

class Cookie
{

    static private $cookie = [];

    static public function cookie($name = null, $value = null)
    {
        if (empty($value)) return self::getCookie($name);
        if (!empty($name) && !empty($value)) return self::setCookie($name, $value);
        return null;
    }

    static private function getCookie($name = null)
    {
        if (empty($name)) {
            return empty(self::$cookie) ? false : self::$cookie;
        }
            if (is_string($name)) {
                if (empty(self::$cookie[$name])) {
                    if (empty(Validate::$instance->validateCookie[$name])) {
                        return null;
                    }

                    if (empty($_COOKIE[$name])) {
                        return null;
                    }

                    if (Validate::$instance->safe([$name => Validate::$instance->validateCookie[$name]], [$name => $_COOKIE[$name]])) {
                        self::$cookie[$name] = $_COOKIE[$name];
                        return self::$cookie[$name];
                    } else {
                        return null;
                    }
                } else {
                    self::$cookie[$name];
                }
            }
        return null;
    }


    static private function setCookie($name, $value = null, $expire = 0, $path = '/', $domain = null, $secure = null, $httponly = false)
    {
        if (is_string($name) && is_string($value)) {
            setcookie($name, $value, $expire, $path , $domain, $secure, $httponly = false);
            return $value;
        }
        return false;
    }

    static public function delete($name, $path = null)
    {
        if (is_string($name)) {
            if (setcookie($name, null, time() - 24 * 3600, $path = null)) {
                return true;
            }
        }
        return false;
    }
}