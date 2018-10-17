<?php
/**
 * Created by PhpStorm.
 * User: BITCC
 * Date: 2018/9/26
 * Time: 15:16
 */

namespace Core;


use Core\Http\Request;
use Core\Http\Cookie;
/**
 * Class Controller
 * @package Core
 */
abstract class Controller
{
    /**
     * Controller constructor.
     */
    public function __construct()
    {
        Core::listen();
    }

    /**
     * @param $attributes
     * @return null
     */
    protected function custom($attributes)
    {
        if (is_string($attributes)) {
            return Custom::getCustom($attributes);
        }
        return null;
    }


    protected function validateError()
    {
        return Validate::getinstance()->erros;
    }


    /**
     * @return null
     */
    protected function assgin()
    {
        return null;
    }


    /**
     * @param $arr
     * @param int $options
     */
    protected function json($arr, $options = 0)
    {
        echo json_encode($arr, $options);
    }


}