<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/9/27
 * Time: 18:18
 */

namespace Core;


class Validate
{
    /**
     * @var array
     */
    public $validateData = [];


    /**
     * @var array
     */
    public $validateCookie = [];


    /**
     * 声明私有构造方法为了防止外部代码使用new来创建对象
     * Validate constructor.
     */
    private function __construct()
    {
    }


    /**
     * @var
     */
    static public $instance;


    /**
     * 绑定参数的错误信息
     * @var
     */
    public $bindingParamError = [];


    public $Error = null;

    public $request;

    public $cookie = [];



    /**声明一个静态变量（保存在类中唯一的一个实例）
     * @return Validate
     */
    static public function getinstance()
    {
        if (!self::$instance) self::$instance = new self();
        return self::$instance;
    }

    /**
     * 添加错误提示
     * @param $arr
     * @return array|null
     */
    public function paramError($arr)
    {
        if (empty($arr)) {
            return self::$instance;
        }
        if (!is_array($arr)) {
            return self::$instance;
        }
        foreach ($arr as $k => $v) {
            $this->bindingParamError[$k] = $v;
        }
        return self::$instance;
    }

    /**
     * 添加cookie的验证规则
     * @param $vail
     * @return mixed
     */
    public function rulesCookie($vail)
    {
        if (is_array($vail) && !empty($vail)) {
            foreach ($vail as $name => $rule) {
                $this->validateCookie[$name] = $rule;
            }
        }
        return self::$instance;
    }

    /**
     * 添加请求数据的验证规则
     * @param $vail
     * @return mixed
     */
    public function rulesData($vail)
    {
        if (is_array($vail) && !empty($vail)) {
            foreach ($vail as $name => $rule) {
                $this->validateData[$name] = $rule;
            }
        }
        return self::$instance;
    }

    public function check()
    {
        //检查变量名
        $this->checkName();

        //检查cookie
        $this->checkCookie();

        return self::$instance;
    }


    private function checkName()
    {
        if (!array_key_exists(REQUEST_METHOD, RouteService::$route)) {
            //请求方式不正确
            return $this->bindingParamError;
        }
        $req = Core::req();
        foreach ($this->validateData as $name => $rule) {
            $arr = array_flip(explode('|', $rule));
            if (empty($req) || !array_key_exists($name, $req)) {
                if (!isset($arr['nullable'])) {
                    $this->Error = $this->setError('isNull');
                    return false;
                }
            }
        }

        $this->checkParam($req);
        return true;
    }


    private function checkParam($req)
    {
        //验证
        //.....
        //

        //成功
        $this->request = $req;
        $this->unsetReq(REQUEST_METHOD);
        return true;
    }


    private function checkCookie()
    {

    }


    private function setError($name)
    {
        $this->Error = $this->bindingParamError[$name][config('language')];
        return null;
    }

    /**
     * 销毁没有验证的请求数据
     * @return null
     */
    private function unsetReq($method)
    {
        switch ($method) {
            case 'GET':
                $_GET = null;
                break;

            case 'POST':
                $_POST = null;
                break;
            case 'REQUEST':
                $REQUEST = null;
                break;
            case 'COOKIE':
                $_COOKIE = null;
                break;
            default:
                return null;
        }
        return null;
    }
}