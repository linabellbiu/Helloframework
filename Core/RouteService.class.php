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
     * @var object 实例
     */
    static $instance;


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
     * 绑定的路由回调函数
     * @var array
     */
    static public $callableData = [];


    /**
     * 回调函数
     * @var string
     */
    static $callable = '';


    /**
     * 请求方法
     * @var string
     */
    static $method = '';


    /**
     * 请求的路由名字
     * @var string
     */
    static $name = '';


    /**
     * 路由url
     * @var string
     */
    static $url = '';


    /**
     * 容器注入依赖
     * 依赖验证类Validate
     * @var Validate
     */
    private $validate;


    /**
     * RouteService constructor.
     * @param Validate $validate
     */
    public function __construct(Validate $validate)
    {
        $this->validate = $validate;
    }

    static public function getinstance()
    {
        if (!self::$instance) {

            self::$instance = new self(new Validate());
        }
        return self::$instance;
    }

    /**
     * @param $name
     * @param $url
     * @param callable|null $callable
     * @return mixed
     */
    static public function get($name, $url, callable $callable = null)
    {

        self::regRoute(__GET__, $name, $url, $callable);

        return self::getinstance();
    }


    /**
     * @param string $name
     * @param string $url
     * @param callable|null $callable
     * @return RouteService|object
     */
    static public function post($name = '', $url = '', callable $callable = null)
    {

        self::regRoute(__POST__, $name, $url, $callable);

        return self::getinstance();
    }


    /**
     * @param string $name
     * @param string $url
     * @param callable|null $callable
     * @return RouteService|object
     */
    static public function request($name = '', $url = '', callable $callable = null)
    {

        self::regRoute(__REQUEST__, $name, $url, $callable);

        return self::getinstance();
    }


    /**
     * 注册路由
     * @param $method
     * @param $name
     * @param $url
     * @param $callable
     */
    private static function regRoute($method, $name, $url, $callable)
    {
        if (empty($name)) {

            $name = Heplers::defaultRouteName();

            if (!empty(WEB_INDEX) && WEB_INDEX != '') {
                $name = WEB_INDEX . '\\' . $name;
            }
            $url = '/index';
        }
        try {
            if (!is_string($name) && !is_string($url)) {
                throw new Error('路由格式错误');
            }
            if (!strpos($name, CONTROLLER_METHOD_DELIMIT)) {
                throw new Error('路由  ' . CONTROLLER_METHOD_DELIMIT . ' 找不到');
            }
        } catch (Error $e) {
            $e->errorMessage();
        }

        self::setRouteParam($method, $name, $url, $callable);

        self::bandingcallable();

        self::$route[strtoupper($method)][uconlyfirst($url)] = $name;
    }


    /**
     * 绑定路由需要验证的请求参数
     * @param $method
     * @param $name
     * @param $arg
     * @return RouteService|object
     */
    static private function bindingParam($method, $name, $arg)
    {
        self::$validateData[$method][$name] = $arg;

        return self::getinstance();
    }


    /**
     * 验证请求参数是否安全合法
     * @param $req
     * @return bool|mixed
     */
    public function validate($req)
    {
        if (empty(self::$validateData[self::$method][self::$name])) return true;

        return $this->validate->safe(self::$validateData[self::$method][self::$name], $req);
    }


    /**
     * 注册一个回调函数
     */
    private static function bandingcallable()
    {
        self::$callableData[self::$method][self::$name] = self::$callable;
    }


    /**
     * 设置路由的参数
     * @param $method
     * @param string $name
     * @param string $url
     * @param callable|null $callable
     */
    private static function setRouteParam($method, $name, $url, $callable)
    {

        //设置回调函数
        self::setCallable($callable);


        //设置路由名字
        //后续可能兼容路由别名
        //e.g.indexController@index or name_index
        self::setName($name);


        //设置路由请求方法
        self::setMethod($method);


        //设置路由的url
        self::setUrl($url);
    }


    /**
     * @param $name
     */
    public static function setName($name)
    {
        self::$name = $name;
    }


    /**
     * @param $name
     */
    public static function setMethod($name)
    {
        self::$method = $name;
    }


    /**
     * @param $url
     */
    public static function setUrl($url)
    {
        self::$url = $url;
    }


    /**
     * @param $callable
     */
    public static function setCallable($callable)
    {
        self::$callable = $callable;
    }


    /**
     * 执行路由绑定的回调函数
     * 应该在运行控制器之前执行
     * @param $method
     * @param $name
     * @return bool
     */
    public static function routeCallable($method, $name)
    {
        if (!$callable = self::$callableData[self::$method][self::$name]) {
            return false;
        }
        return $callable();
    }
}