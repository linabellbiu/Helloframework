<?php
/**
 * Created by PhpStorm.
 * User: wangxdong
 * Date: 2019/5/8
 * Time: 20:50
 */

namespace Core;


interface ValidateInterface
{

    /**
     * @param $vaild
     * @param $value
     * @return mixed
     */
    public function safe($vaild, $value);
}