<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/6
 * Time: 15:30
 */

//加载所需要的文件

namespace Core\Hello\Build;

use Core\Custom;
use Core\Error;
use Core\Hello\LoadInterFace;
use Core\Heplers;

class BuildFile implements LoadInterFace
{

    //需要加载的系统配置或者库文件
    //请严格按照顺序加载
    private $need_load_file = [
        'loadSystemCommon',
        'loadSystemConf',
        'loadSystemLib',
        'loadAppRoute',
        'loadAppInt',
    ];


    /**
     * 加载lib
     */
    private function loadSystemLib()
    {
        Heplers::loadFile(CORE_LIB . 'filter.php');
        Heplers::loadFile(CORE_LIB . 'validate.php');
    }


    /**
     * 加载系统配置文件
     */
    private function loadSystemConf()
    {
        config(Heplers::loadFile(SYS_CONFIG_PATH));
    }


    /**
     * 加载公共文件
     */
    private function loadSystemCommon()
    {
        $function_file = CORE_COMMON_PATH . 'function.php';
        try {
            if (file_exists($function_file)) {
                require_once $function_file;
            } else {
                throw new Error(CORE_COMMON_PATH . 'function.php 找不到');
            }
        } catch (Error $e) {
            $e->errorMessage();
        }
    }


    /**
     * 加载路由
     */
    private function loadAppRoute()
    {
        Heplers::loadFile(APP_ROUTE);
    }


    /**
     * 加载应用初始化模块
     */
    private function loadAppInt()
    {
        foreach (Heplers::loadFile(APP_INTI) as $name => $mod)
            foreach ($mod as $conf)
                Heplers::loadFile(APP_PATH . $name . '/' . $conf . '.php');
    }


    /**
     * 加载app配置文件
     * @param $file
     */
    private function loadAppConfig($file)
    {
        foreach ($file as $app_config) {
            config(Heplers::loadFile(APP_CONFIG_PATH . $app_config . CONFIG));
        }
    }


    /**
     * 加载语言包
     */
    private function loadAppLanguage()
    {
        Custom::setCustom(Heplers::loadFile(APP_LANGUAGE_PATH . config('language') . '.php'));
    }


    public function load()
    {
        try {
            foreach ($this->need_load_file as $package) {
                if (method_exists($this, $package)) {
                    $this->{$package}();
                } else {
                    throw new Error('方法' . $package . '在' . get_class() . '找不到');
                }
            }
        } catch (Error $e) {
            $e->errorMessage();
        }
    }
}