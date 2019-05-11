<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/9/25
 * Time: 9:57
 */

namespace App\controller;

use Core\Controller;
use Core\Http\Cookie;
use Core\Http\Request;
use App\model\index\IndexModel;
use View\View;

class IndexController extends Controller
{
    public function index()
    {
        view('welcome', 'test', 'Hello word');
    }
}