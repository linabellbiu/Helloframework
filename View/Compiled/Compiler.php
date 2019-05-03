<?php
/**
 * Created by PhpStorm.
 * User: wangxdong
 * Date: 2019/4/20
 * Time: 12:23
 */

namespace View\Compiled;

use View\Support\Filesystem;
use View\Support\Str;

abstract class Compiler
{
    protected $files;

    protected $cachePath;

    public function __construct(Filesystem $filesystem, $cachePath)
    {
        $this->files = $filesystem;
        $this->cachePath = $cachePath;
    }

    public function getCompiledPath($path)
    {
        return $this->cachePath . '/' . sha1($path) . '.php';
    }

    public function isExpired($path)
    {
        $compiled = $this->getCompiledPath($path);

        if (!$this->files->fileExist($compiled)) {
            return true;
        }

        if ($this->files->lastModified($path) >
            $this->files->lastModified($compiled)) {
            return true;
        }
        return false;
    }
}