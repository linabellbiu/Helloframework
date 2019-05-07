<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/6
 * Time: 16:45
 */

namespace Core\Hello\Build;

use Core\Factory;

class SystemBuild
{
    public static function run()
    {
        self::bind();

        Factory::make('BuildFile')->LoadComponent();

        Factory::make('BuildView')->LoadComponent();
    }

    static function bind()
    {
        Factory::bind('BuildFile', function () {
            return new Load(new BuildFile());
        });

        Factory::bind('BuildView', function () {
            return new Load(new BuildView());
        });
    }
}