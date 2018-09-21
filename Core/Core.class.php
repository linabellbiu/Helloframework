<?php
namespace Core;

use Core\lib\CoreLogic;

class Core extends CoreLogic
{
    public static function start()
    {
       $req =  self::request();
       var_dump($req);
    }


    public static function run()
    {

    }

    public static function request()
    {
        return self::reqLogic(strtolower($_SERVER['REQUEST_METHOD']));
    }



}