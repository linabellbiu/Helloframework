<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/7
 * Time: 19:14
 */


namespace Core\Hello\Middleware;

class Auth
{
    public function __construct()
    {
        echo 'this is auth';
    }
}