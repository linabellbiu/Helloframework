<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/6
 * Time: 16:18
 */

namespace Core\Hello\Build;

use View\View;
use Core\Factory;
use View\ViewFactory;
use View\FileViewFinder;
use View\Support\Filesystem;
use Core\Hello\LoadInterFace;
use View\Compiled\HelloCompiler;
use View\Engine\CompilerEngine;

class BuildView implements LoadInterFace
{

    public function load()
    {
        //绑定模板系统文件助手
        Factory::bind('Filesystem', function () {
            return new Filesystem();
        });

        //绑定编译器
        Factory::bind('HelloCompiler', function () {
            return new HelloCompiler(Factory::make('Filesystem'), config('templet_cache_path'));
        });

        //绑定编译引擎
        Factory::bind('CompilerEngine', function () {
            return new CompilerEngine(Factory::make('HelloCompiler'));
        });

        //绑定模板文件阅读器
        Factory::bind('FileViewFinder', function () {
            $FileViewFinder = new FileViewFinder(Factory::make('CompilerEngine'), Factory::make('HelloCompiler'));
            $FileViewFinder->path = config('template_path');
            return $FileViewFinder;
        });

        //绑定模板工厂
        Factory::bind('Factory', function () {
            return new ViewFactory(Factory::make('FileViewFinder'));
        });

        //绑定视图
        Factory::bind('View', function () {
            return new View(Factory::make('Factory'), Factory::make('CompilerEngine'));
        });
    }
}