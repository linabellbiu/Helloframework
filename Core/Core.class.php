<?php
namespace Core;

//use Core\lib\CoreLogic;
class Core
//class Core extends CoreLogic
{

    public static function start()
    {
        self::request();
        echo "adasd";
    }


    public static function run()
    {

    }

    public static function request()
    {
//        self::reqLogic(strtolower($_SERVER['REQUEST_METHOD']));
    }



}