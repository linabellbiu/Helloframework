<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/9/25
 * Time: 11:56
 */


/**
 * 只允许英文字母字母
 * @param $str
 * @return bool
 */
function only_english_letter($str)
{
    return preg_match('#^[a-z]+$#i', $str) ? true : false;
}

/**
 * 加载配置，但是没有正真的设置
 * @param $filname
 * @return mixed|null
 */
function load_config($filname)
{
    return file_exists($filname) ? include $filname : null;
}


/**
 * 获取和设置配置参数 支持批量定义
 * @param string|array $name 配置变量
 * @param mixed $value 配置值
 * @param mixed $default 默认值
 * @return mixed
 */
function config($name = null, $value = null, $default = null)
{
    static $_config = array();
    // 无参数时获取所有
    if (empty($name)) {
        return $_config;
    }
    // 优先执行设置获取或赋值
    if (is_string($name)) {
        if (!strpos($name, '.')) {
            $name = strtoupper($name);
            if (is_null($value)) {
                return isset($_config[$name]) ? $_config[$name] : $default;
            }

            $_config[$name] = $value;
            return null;
        }
        // 二维数组设置和获取支持
        $name = explode('.', $name);
        $name[0] = strtoupper($name[0]);
        if (is_null($value)) {
            return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : $default;
        }

        $_config[$name[0]][$name[1]] = $value;
        return null;
    }
    // 批量设置
    if (is_array($name)) {
        $_config = array_merge($_config, array_change_key_case($name, CASE_UPPER));
        return null;
    }
    return null; // 避免非法参数
}

/**
 * 只有首字母大写
 * @param $string
 * @return string
 */
function uconlyfirst($string)
{
    return ucfirst(strtolower($string));
}



function validate($param)
{


}