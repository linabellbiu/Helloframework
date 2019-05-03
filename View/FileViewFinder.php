<?php
/**
 * Created by PhpStorm.
 * User: wangxdong
 * Date: 2019/4/14
 * Time: 20:51
 */

/**
 * 文件阅读器
 */

namespace View;

use View\Compiled\Compiler;
use View\Support\Filesystem;
use View\Engine\EngineInterface;

class FileViewFinder
{
    public $path = '';

    public $templetPath;

    protected $extensions = ['aex.html', 'html', 'aex.php', 'php'];

    private $engine;

    public function __construct(EngineInterface $engine, Compiler $compiler)
    {
        $this->engine = $engine;
    }

    public function findTempletPath($name)
    {
        foreach ($this->extensions as $extension) {

            $path = $this->path . Filesystem::normalize($name) . '.' . $extension;

            if (Filesystem::fileExist($path)) {
                $this->templetPath = $path;
                break;
            }
        }
        return $this->templetPath;
    }

    public function getContent($path, $data)
    {
        return $this->engine->get($path, $data);
    }
}