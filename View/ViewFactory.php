<?php
/**
 * Created by PhpStorm.
 * User: wangxdong
 * Date: 2019/4/14
 * Time: 20:30
 */

namespace View;

use View\FileViewFinder;

class ViewFactory
{
    private $fileViewFinder;

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
}