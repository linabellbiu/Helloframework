<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/9/27
 * Time: 18:18
 */

namespace Core;

use Core\Http\Request;

class Validate implements ValidateInterface
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
     * 绑定参数的错误信息
     * @var
     */
    public $bindingParamError = [];


    public $errors;


    /**
     * 添加错误提示
     * @param $arr
     * @return $this
     */
    public function bandingError($arr)
    {
        if (empty($arr)) {
            return $this;
        }
        if (!is_array($arr)) {
            return $this;
        }
        foreach ($arr as $k => $v) {
            $this->bindingParamError[$k] = $v;
        }
        return $this;
    }


    /**
     * 添加cookie的验证规则
     * @param $vail
     * @return $this
     */
    public function Cookie($vail)
    {
        if (is_array($vail) && !empty($vail)) {
            foreach ($vail as $name => $rule) {
                $this->validateCookie[$name] = $rule;
            }
        }
        return $this;
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
        return $this;
    }


    /**
     * 传入规则和请求数据调用安全库开始验证
     * @param $valid array 验证规则
     * @param $value array 验证参数
     * @return bool|mixed
     */
    public function safe($valid, $value)
    {
        $valid = (array)$valid;
        $value = (array)$value;

        foreach ($valid as $name => $rule) {

            $rules = array_flip(explode('|', $rule));

            //首先检查这个参数是否为空，如果是允许空值并且是空值那就进行下一个参数检查，否则继续下一项检查
            if (empty($value) || !array_key_exists($name, $value) || count($value) == 0) {
                if (isset($rules['null'])) {
                    unset($rules['null']);
                    continue;
                }

                //没有设置空设置错误信息
                if (empty($this->getError($name . '|null'))) {
                    $this->errors = $name . ' not is null';
                } else {
                    $this->setError($name . '|null');
                }
                return false;
            }

            foreach (array_flip($rules) as $ruleType) {
                if (Filter::getinstance()->filter($value[$name], $ruleType)) {
                    continue;
                } else {
                    if (empty($name)) {
                        $this->setError($ruleType);
                    } else {
                        $this->setError($name . '|' . $ruleType);
                    }
                    return false;
                }
            }
        }
        return true;
    }


    private function setError($name)
    {
        if (empty($this->bindingParamError[$name][config('language')])) {
            $this->errors = $this->bindingParamError[$name];
        } else {
            $this->errors = $this->bindingParamError[$name][config('language')];
        }
        return;
    }


    private function getError($name)
    {
        return empty($this->bindingParamError[$name]) ? null : $this->bindingParamError[$name];
    }


    private function unsetReq($name = null)
    {
        Request::delete($name);
    }
}