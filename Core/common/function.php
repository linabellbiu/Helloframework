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
 * 加载文件，但是没有正真的设置
 * @param $filname
 * @return mixed|null
 */
function load_file($filname)
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

/**
 * 输出视图
 * @param null $args
 */
function view($args = null)
{
    $count = func_num_args();
    $varArray = func_get_args();

    $view = APP_MODULE . '.' . __M__;
    $value = [];
    switch ($count) {
        case 0:
            break;
        case 1:
            $data = $varArray[0];
            do {
                if (is_array($data)) {
                    $value = $data;
                    break;
                }
                if (strpos($data, '.') === false) {
                    $view = APP_MODULE . '.' . $data;
                }
                break;
            } while (0);
            break;
        case 2:
            $name = $varArray[0];
            $data = $varArray[1];
            if (is_array($data)) {
                if (is_string($name)) {
                    if (strpos($name, '.') === false) {
                        $value[$name] = $data;
                    } elseif (strpos($name, '.') !== false) {
                        $view = $name;
                        $value = $data;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                if (is_string($name)) {
                    $value[$name] = $data;
                }
            }
            break;
        case 3:
            $view = $varArray[0];
            $name = $varArray[1];
            $data = $varArray[2];
            if (strpos($view, '.') === false) {
                $view = APP_MODULE . '.' . $view;
            }
            if (is_array($name)) {
                $value = $name;
            } elseif (is_string($name)) {
                $value[$name] = $data;
            }
            break;
        default:
    }

    exit(\View\Factory::make('View')->make(strtolower($view), $value)->render());
}

function debug_var($val){
    var_dump($val);
    exit;
}

//获取当前请求的url
function getReqUrl(){
    return REQUEST_URI;
}

function htmlencode($string,$flags){
    return htmlentities($string,$flags);
}