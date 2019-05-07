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
        'loadAppValidate',
        'loadRoute',
        'loadAppInt',
    ];


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
            echo $e->getMessage();
        }
    }

    private function loadAppRoute()
    {
        Heplers::loadFile(APP_ROUTE);
    }

    private function loadAppValidate()
    {
        Heplers::loadFile(APP_RULE);
    }

    private function loadAppInt()
    {
        foreach (Heplers::loadFile(APP_INTI) as $name => $mod) {

            if ($name == APP_CONFIG) $this->loadAppConfig($mod);

            if ($name == APP_LANGUAGE) $this->loadAppLanguage();
        }
    }

    private function loadAppConfig($file)
    {
        foreach ($file as $app_config) {
            config(Heplers::loadFile(APP_CONFIG_PATH . $app_config . CONFIG));
        }
    }

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
            $e->getMessage();
        }

    }
}