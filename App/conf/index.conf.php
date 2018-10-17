<?php
/**
 * Created by PhpStorm.
 * User: BITCC
 * Date: 2018/9/26
 * Time: 19:10
 */

use Core\Http\Cookie;


if (!Cookie::cookie('language')) {
    $language = config('language');
} else {
    $language = Cookie::cookie('language');
}


return [
    'h' => true,
    'language' => $language
];