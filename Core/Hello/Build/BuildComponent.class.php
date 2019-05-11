<?php
/**
 * Created by PhpStorm.
 * User: wangxdong
 * Date: 2019/5/8
 * Time: 21:09
 */

namespace Core\Hello\Build;

use Core\Factory;
use Core\Hello\LoadInterFace;
use Core\Http\Cookie;
use Core\RouteService;
use Core\Validate;


/**
 * 加载各种组件
 * Class BuildComponent
 * @package Core\Hello\Build
 */
class BuildComponent implements LoadInterFace
{

    /**
     * 加载验证器
     * @return string
     */
    private function loadValidate()
    {
        Factory::bind('Validate', function () {
            return new Validate();
        });

        return 'Validate';
    }


    /**
     * 加载路由服务
     */
    private function loadRouteValidate()
    {
        Factory::bind('RouteService', function () {
            return new  RouteService(Factory::make($this->loadValidate()));
        });
        Factory::make('RouteService');
    }


    private function loadCookie()
    {
        Factory::bind('Cookie', function () {
            return new Cookie(Factory::make($this->loadValidate()));
        });

        Factory::make('Cookie');
    }


    /**
     * 加载器
     */
    public function load()
    {
        $this->loadRouteValidate();
        $this->loadCookie();
    }
}