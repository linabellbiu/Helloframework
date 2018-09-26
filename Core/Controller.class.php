<?php
/**
 * Created by PhpStorm.
 * User: BITCC
 * Date: 2018/9/26
 * Time: 15:16
 */

namespace Core;

abstract class Controller
{

    protected $config = [];


    /**
     * 设置配置文件
     * @param $configdir
     * @return bool
     */
    protected function setConf($configdir)
    {
        $dir = APP_PATH . 'common/conf/' . $configdir . CONFIG;
        if (!file_exists($dir))return false;
        $this->config = require ($dir);
        return true;
    }

    /**
     * 获取配置文件
     * @param $conf
     * @return array|mixed
     */
    protected function getConf($conf)
    {
        //传过来的是数组
        if (is_array($conf))
        {
            $data = [];
            foreach ($conf as $k)
            {
                if (!in_array($k,$this->config)) continue;
                $data[$k] = $this->config[$k];
            }
            return $data;
        }

        if (!in_array($conf,$this->config)) return [];
        return $this->config[$conf];
    }
}