<?php
/**
 * Created by PhpStorm.
 * User: BITCC
 * Date: 2018/9/26
 * Time: 19:10
 */

use Core\Http\Cookie;

return [
    'h'=>true,
    'language'=>Cookie::cookie('language'),
];