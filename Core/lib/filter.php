<?php
/**
 * Created by PhpStorm.
 * User: wangxdong
 * Date: 2019/5/4
 * Time: 13:57
 */

/**
 * @param $string
 * @return bool|string
 */
function __controller($string)
{
    return is_string($string) ? htmlencode(trim($string), ENT_QUOTES) : false;
}


/**
 * @param $string
 * @return bool|string
 */
function __mothed($string)
{
    return is_string($string) ? htmlencode(trim($string), ENT_QUOTES) : false;
}

function __string($string)
{
    return is_string($string) ? htmlencode(is_string($string), ENT_QUOTES) : false;
}