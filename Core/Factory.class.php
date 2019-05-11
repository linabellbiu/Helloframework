<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/6
 * Time: 16:49
 */

namespace Core;

use function PHPSTORM_META\type;

abstract class Factory
{

    protected static $registry = [];

    public static function bind($name, callable $callable)
    {
        static::$registry[$name] = $callable;
    }

    public static function make($name)
    {
        if (isset(static::$registry[$name])) {
            $resolver = static::$registry[$name];
            return $resolver();
        } else {
            throw new Error('make err');
        }
    }
}