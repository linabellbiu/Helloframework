<?php
/**
 * Created by PhpStorm.
 * User: wangxdong
 * Date: 2019/4/20
 * Time: 11:22
 */

namespace View\Engine;

interface EngineInterface
{
    public function get($path,array $data=[]);
}