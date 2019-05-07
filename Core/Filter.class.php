<?php

/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/10/15
 * Time: 10:09
 */
namespace Core;

use Core\Error;

class Filter
{

    static public $instance;

    private function __construct()
    {
    }

    /**
     * @return Filter
     */
    static public function getinstance()
    {
        if (!self::$instance) self::$instance = new self();
        return self::$instance;
    }

    static private function lib($string)
    {
        switch ($string) {
            case 'mail':                    //邮箱验证
                return FILTER_VALIDATE_EMAIL;
            case 'ipv6':                    //ipv6验证
                return FILTER_VALIDATE_IP;
            case 'url':                     //默认的普通url
                return FILTER_VALIDATE_URL;
            case 'urlscheme':               //要求 URL 是 RFC 兼容 URL(比如 http://example)
                return [FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED];
            case 'urlhost':                 // 要求 URL 包含主机名(比如 http://www.example.com)
                return [FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED];
            case 'urlpath':                 //要求 URL 在域名后存在路径(比如 www.example.com/example1/test2/)
                return [FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED];
            case 'urlquery':                 //要求 URL 存在查询字符串(比如 "example.php?name=Peter&age=37")
                return [FILTER_VALIDATE_URL, FILTER_FLAG_QUERY_REQUIRED];
            default:                        //自定义
                return FILTER_CALLBACK;

        }
    }

    static public function filter($string, $filter)
    {
        $valid = self::lib($filter);
        if ($valid == FILTER_CALLBACK) {
            try {
                if (!function_exists('__' . $filter)) throw new Error('__' . $filter . '找不到');
            } catch (Error $e) {
                $e->errorMessage();
            }

            return filter_var($string, FILTER_CALLBACK, [
                'options' => '__' . $filter
            ]);
        } else {
            if (is_int($valid)) {
                return filter_var($string, $valid);
            }
            if (is_array($valid)) {
                return filter_var($filter, $valid[0], $valid[1]);
            }
        }
        return false;
    }
}