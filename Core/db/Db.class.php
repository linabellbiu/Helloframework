<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2019/2/27
 * Time: 22:16
 */
//==============
//数据库中间层
//=============
namespace Core\db;

use Core\Error;

class Db
{

    //当前数据库连接实例
    static private $instance = array();

    //  当前数据库连接实例
    static private $_instance = null;

    static function getInstance($config = array())
    {
        $md5 = md5(serialize($config));
        try {
            do {
                if (!empty(self::$instance[$md5])) {
                    break;
                }

                $type = $config["type"];
                $class = 'Core\\db\\driver\\' . ucwords(strtolower($type));
                if (class_exists($class)) {
                    self::$instance[$md5] = new $class($config);
                    break;
                }

                throw new Error("没有找到" . $class);

            } while (0);
        } catch (Error $e) {
            $e->errorMessage();
        }

        self::$_instance = self::$instance[$md5];
        return self::$_instance;
    }
}