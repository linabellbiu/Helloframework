<?php
/**
 * Created by PhpStorm.
 * User: wangxdong
 * Date: 2019/4/21
 * Time: 13:32
 */

namespace View\Compiled;

interface CompilerInterface
{
    public function compile($path);

    public function getCompiledPath($path);
}