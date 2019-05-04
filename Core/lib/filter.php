<?php
/**
 * Created by PhpStorm.
 * User: wangxdong
 * Date: 2019/5/4
 * Time: 13:57
 */

function __controller($string)
{
    return is_string($string) ? htmlencode(trim($string),ENT_QUOTES) : false;
}

function __mothed($string)
{
    return is_string($string) ? htmlencode(trim($string),ENT_QUOTES) : false;
}