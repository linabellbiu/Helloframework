<?php
/**
 * Created by PhpStorm.
 * User: wangxdong
 * Date: 2019/4/14
 * Time: 20:30
 */

namespace View;

use View\FileViewFinder;

class Factory
{
    private $fileViewFinder;

    protected static $registry = [];

    public function __construct(FileViewFinder $fileViewFinder)
    {
        $this->fileViewFinder = $fileViewFinder;
    }

    public function find($view)
    {
        return $this->fileViewFinder->findTempletPath($view);
    }

    public function exec($path,$data)
    {
        return $this->fileViewFinder->getContent($path,$data);
    }

    public static function bind($name, callable $callable)
    {
        static::$registry[$name] = $callable;
    }

    public static function make($name)
    {
        if (isset(static::$registry[$name])) {
            $resolver = static::$registry[$name];
            return $resolver();
        }
        throw new \Exception('make err');
    }
}