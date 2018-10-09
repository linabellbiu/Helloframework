<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/9/25
 * Time: 9:57
 */

namespace App\controller;

use Core\Controller;
use Core\RouteService;
use Core\Validate;

class IndexController extends Controller
{


    function index()
    {
      echo $this->reqError();
    }
}
