<?php
/**
 * Created by PhpStorm.
 * User: wangxdong
 * Date: 2019/4/20
 * Time: 12:23
 */

namespace View\Engine;

use View\AexEngine;
use View\Compiled\CompilerInterface;
use View\Support\Filesystem;
use View\Support\Str;


class CompilerEngine extends AexEngine
{
    private $compiler;

    public function __construct(CompilerInterface $compiler)
    {
        $this->compiler = $compiler;
    }

    public function get($path, array $data = [])
    {
        $this->compiler->compile($path);

        $result = $this->evaluatePath($this->compiler->getCompiledPath($path), $data);

        return $result;
    }
}