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
use Core\ValidateInterface;

class Cookie
{

    /**
     * 实例化的对象
     * @var ValidateInterface
     */
    private $validate;


    /**
     * 验证的字段和规则
     * @var array
     */
    static $validateData = [];


    /**
     * 绑定错误
     * @var array
     */
    static $validateError = [];


    /**
     * cookie类
     * @var Cookie
     */
    static $instance;


    /**
     * @var string
     */
    static $method = 'COOKIE';


    /**
     * 验证完cookie最后的结果集
     * @var array
     */
    static private $cookie = [];


    /**
     * Cookie constructor.
     * @param ValidateInterface $validate
     */
    public function __construct(ValidateInterface $validate)
    {
        $this->validate = $validate;

        self::$instance = $this;
    }


    /**
     * @return Cookie
     */
    static public function getinstance()
    {
        return self::$instance;
    }


    /**
     * 设置name,value 就是设置cookie,如果不设置name或者name为数组就是获取cookie
     * @param null $name
     * @param null $value
     * @param int $expire
     * @param string $path
     * @param null $domain
     * @param null $secure
     * @param bool $httponly
     * @return array|null
     */
    static public function cookie($name = null, $value = null, $expire = 0, $path = '/', $domain = null, $secure = null, $httponly = false)
    {
        if (empty($value))
            return self::getCookie($name);

        if (!empty($name) && !empty($value))
            return self::setCookie($name, $value, $expire = 0, $path = '/', $domain = null, $secure = null, $httponly = false);

        return null;
    }


    /**
     * 获取cookie
     * @param null $name
     * @return array|null
     */
    static public function getCookie($name = null)
    {
        if (empty($name)) {
            return isset(self::$cookie) ? self::$cookie : null;
        }

        return isset(self::$cookie[$name]) ? self::$cookie[$name] : null;
    }


    /**
     * @param $name
     * @param null $value
     * @param int $expire
     * @param string $path
     * @param null $domain
     * @param null $secure
     * @param bool $httponly
     * @return null
     */
    static private function setCookie($name, $value = null, $expire = 0, $path = '/', $domain = null, $secure = null, $httponly = false)
    {
        if (is_string($name) && is_string($value)) {
            setcookie($name, $value, $expire, $path, $domain, $secure, $httponly = false);

            self::$cookie[$name] = $value;

            return $value;
        }
        return null;
    }


    /**
     * 綁定cookie验证规则
     * @param $arg
     * @return Cookie
     */
    public static function bindingParam($arg)
    {
        if (is_array($arg)) {
            foreach ($arg as $name => $route) {
                self::$validateData[$name] = $route;
            }
            self::getinstance()->validate();
        }
        return self::getinstance();
    }


    public function bindingError($arg)
    {
        foreach ($arg as $names => $rout) {
            list($name,) = explode('|', $names);
            self::$validateError[self::$method][$name] = $arg;
        }

        return self::getinstance();
    }


    /**
     * 验证cookie参数
     * @return array
     */
    private function validate()
    {
        foreach (self::$validateData as $name => $route) {

            if (isset(self::$cookie[$name])) {
                continue;
            }

            $value = isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;

            if (!isset($value)) {
                continue;
            }

            if (!empty(self::$validateError[self::$method][$name]))
            {
                $this->validate->bindingError(self::$validateError[self::$method][$name]);
            }

            if (!self::getinstance()->validate->safe($route, $name)) {
                continue;
            }
            self::$cookie[$name] = $value;
        }

        return self::cookieResult();
    }


    /**
     * cookie验证通过的结果集
     * @return array
     */
    static private function cookieResult()
    {
        return self::$cookie;
    }


    /**
     * 删除这个cookie
     * @param $name
     * @param null $path
     */
    static public function delete($name, $path = null)
    {
        $names = (array)$name;

        if (is_array($names)) {
            foreach ($names as $name) {
                if (setcookie($name, null, time() - 24 * 3600, $path = null)) {
                    unset(self::$cookie[$name]);
                }
            }
        }
    }
}