<?php

namespace Core;

use Core\Http\Request;


class Core
{
    public static function start()
    {

        App::init();

        //映射url到控制器
        App::dispatch();

        //执行应用程序
        App::exec();
    }


    public static function run()
    {
        try {
            self::start();
        } catch (Error $e) {
            $e->errorMessage();
        }
    }

    public static function listen()
    {
        Request::request();
    }
}