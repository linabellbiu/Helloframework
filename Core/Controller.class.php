<?php
/**
 * Created by PhpStorm.
 * User: BITCC
 * Date: 2018/9/26
 * Time: 15:16
 */

namespace Core;

abstract class Controller
{
    function __construct()
    {
        $this->intit();
    }


    private function intit()
    {
        $this->paramValidate();
    }

    private function paramValidate()
    {
        $req = Core::req();
    }
}