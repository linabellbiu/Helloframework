<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/9/25
 * Time: 11:56
 */

function only_english_letter($str)
{
    return preg_match('#^[a-z]+$#i', $str) ? true : false;
}