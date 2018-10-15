<?php
namespace Core;

use Core\Lib\Filter;

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
        self::start();
    }

    public static function listen()
    {
        Filter::getinstance();
        Validate::getinstance();
        Custom::getinstance();
    }
}