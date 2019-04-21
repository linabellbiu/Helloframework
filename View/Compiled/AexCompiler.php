<?php
/**
 * Created by PhpStorm.
 * User: wangxdong
 * Date: 2019/4/20
 * Time: 12:23
 */

namespace View\Compiled;

use View\Support\Filesystem;

class AexCompiler extends Compiler implements CompilerInterface
{

    protected $path;

    protected function compileStatements($value)
    {
        return preg_replace_callback(
            '/\B@(@?\w+(?:::\w+)?)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))?/x', function ($match) {
            return $this->compileStatement($match);
        }, $value
        );
    }

    protected function compileStatement($match)
    {
        return isset($match[3]) ? $match[1] . $match[3] : $match[1];
    }

    public function compile($path)
    {
        if ($path) {
            $this->setPath($path);
        }

        $result = $this->compileStatements(($this->files->get($path)));

        $this->files->put($this->getCompiledPath($this->path), $result);
    }

    public function setPath($path)
    {
        $this->path = $path;
    }
}