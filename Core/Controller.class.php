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
use Core\Factory;

/**
 * Class Controller
 * @package Core
 */
abstract class Controller
{

    private $temp_data = [];

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
        //return Validate::getinstance()->erros;
    }

    /**
     * @param $arr
     * @param int $options
     */
    protected function json($arr, $options = 0)
    {
        echo json_encode($arr, $options);
    }


    protected function render($name = null)
    {
        $view = Factory::make('View');

        if (empty($name)) {
            list($name,) = explode('Controller', __C__);
        }
        exit($view->make(strtolower($name), $this->temp_data)->render());
    }


    protected function assgin($param = null, $param2 = null)
    {
        if (is_array($param)) {
            $this->temp_data = $param;
        } else {
            if (is_string($param)) {
                $this->temp_data[$param] = $param2;
            }
        }
        return $this;
    }
}