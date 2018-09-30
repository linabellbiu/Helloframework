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
     * 参数绑定
     * 从路由类获取
     * @var
     */
    private $validateData;

    /**
     * 绑定参数的错误信息
     * @var
     */
    public $bindingParamError = [];


    function __construct()
    {
        $this->init();
    }

    /**
     * 初始化
     * 单列模式，防止多次实例化
     * @return mixed
     */
    private function init()
    {
        if (empty(RouteService::$validate)) {
            return RouteService::$validate;
        }
        $this->validateData = RouteService::$validateData;
    }

    /**
     * 添加错误提示
     * @param $arr
     * @return array|null
     */
    public function paramError($arr)
    {
        if (empty($arr)) {
            return $this->bindingParamError;
        }
        foreach ($arr as $k => $v) {
            $this->bindingParamError[$k] = $v;
        }
        return null;
    }




}