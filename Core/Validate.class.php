<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/9/27
 * Time: 18:18
 */

namespace Core;

use Core\Http\Request;

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


    public $erros;


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
    public function bandingError($arr)
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
    public function Cookie($vail)
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
     * @param array $vail 验证规则
     * @return mixed
     */
    public function Data($vail)
    {
        if (is_array($vail) && !empty($vail)) {
            foreach ($vail as $name => $rule) {
                $this->validateData[$name] = $rule;
            }
        }
        return self::$instance;
    }


    /**
     * 传入规则和请求数据调用安全库开始验证
     * @param $vaild array 验证规则
     * @param $value array 验证参数
     * @return bool|mixed
     */
    public function safe($vaild, $value)
    {
        try {
            if (!is_array($vaild)) throw new Error('验证规则因该是数组');
            if (!is_array($value)) throw new Error('验证数据因该是数组');
        } catch (Error $e) {
            $e->errorMessage();
        }
        if (empty($vaild)) {
            return false;
        }
        foreach ($vaild as $name => $rule) {
            $arr = array_flip(explode('|', $rule));

            //验证是否允许是空的传值
            if (empty($value) || !array_key_exists($name, $value)) {
                if (!isset($arr['null'])) {
                    if (empty($this->getError($name . '|null'))) {
                        $this->setError('SYS_ROUTE_REQ_IS_NULL');
                    } else {
                        $this->setError($name . '|null');
                    }
                    return false;
                }
                continue;
            }
            foreach (array_flip($arr) as $index => $rules) {
                if (Filter::getinstance()->filter($value[$name], $rules)) {
                    return true;
                } else {
                    if (empty($name)) {
                        $this->setError($rules);
                    } else {
                        $this->setError($name . '|' . $rules);
                    }
                    return false;
                }
            }
        }
        return false;
    }


    private function setError($name)
    {
        if (empty($this->bindingParamError[$name][config('language')])) {
            $this->erros = $this->bindingParamError[$name];
        } else {
            $this->erros = $this->bindingParamError[$name][config('language')];
        }
        return;
    }


    private function getError($name)
    {
        return $this->bindingParamError[$name] ? $this->bindingParamError[$name] : null;
    }

    private function unsetReq($name = null)
    {
        Request::delete();
    }
}