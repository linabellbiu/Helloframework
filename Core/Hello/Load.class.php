<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/6
 * Time: 16:19
 */

namespace Core\Hello\Build;


use Core\Hello\LoadInterFace;

class Load
{
    private $loadInterFace;

    public function __construct(LoadInterFace $loadInterFace)
    {
        $this->loadInterFace = $loadInterFace;
    }

    public function LoadComponent()
    {
        $this->loadInterFace->load();
    }
}