<?php
namespace Core;

class Core
{
    public static function start()
    {
        //初始化配置
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

    public static function reqFrom()
    {

    }
}