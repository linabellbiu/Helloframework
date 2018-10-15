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
            return empty(self::$cookie[$name]) ? false : self::$cookie[$name];
        }

        if (is_array($name)) {
            if (empty($_COOKIE))
            {
                return false;
            }

            $c = [];
            foreach ($name as $k) {
                 if($k == 'all')
                 {
                  array_push($c,$_COOKIE);
                 }else
                 {
                     $c[$k] = $_COOKIE[$k];
                 }
            }
            if (Validate::$instance->safe($name, $c)) {
                self::$cookie = $c;
                array_push(self::$cookie,$c);
                return $c;
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