<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/6
 * Time: 16:45
 */

namespace Core\Hello\Build;

use Core\Factory;

/**
 * 构建系统必要组件
 * Class SystemBuild
 * @package Core\Hello\Build
 */
class SystemBuild
{
    public static function run()
    {
        self::bind();

        Factory::make('BuildComponent')->LoadComponent();

        Factory::make('BuildFile')->LoadComponent();

        Factory::make('BuildView')->LoadComponent();
    }

    static function bind()
    {
        Factory::bind('BuildComponent', function () {
            return new Load(new BuildComponent());
        });

        Factory::bind('BuildFile', function () {
            return new Load(new BuildFile());
        });

        Factory::bind('BuildView', function () {
            return new Load(new BuildView());
        });
    }
}