<?php
/**
 * Created by PhpStorm.
 * User: BITCC
 * Date: 2018/9/26
 * Time: 15:16
 */

namespace Core;

/**
 * Class Controller
 * @package Core
 */
abstract class Controller
{
    /**
     * Controller constructor.
     */
    function __construct()
    {
        $this->paramValidate();
    }

    /**
     * 验证请求数据
     */
    protected function paramValidate()
    {
        return Validate::getinstance()->check();
    }

    /**
     * 获取请求数据
     * @return mixed
     */
    protected function request()
    {
        return Validate::getinstance()->request;
    }

    /**
     * 获取请求数据错误信息
     * @return null
     */
    protected function reqError()
    {
       return Validate::getinstance()->Error;
    }

    protected function getCookie($name=null)
    {
        if (empty($name))
        {
            return Validate::getinstance()->cookie;
        }

        return Validate::getinstance()->cookie[$name];
    }
}