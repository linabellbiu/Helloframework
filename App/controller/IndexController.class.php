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
use Core\Middleware;
use View\View;

class IndexController extends Controller
{
    public function index()
    {
        if (!Request::request()) {
            var_dump($this->getReqError());
        }
        var_dump(Cookie::cookie('language'));
//        echo $this->validateError();
//        $test = new IndexMode l("wang", config('db1'));
//        $test->exec();
        view('welcome', 'test', 'Hello word');
    }

    public function login()
    {
        view('index.login');
    }

    public function postLogin()
    {
        var_dump(Request::request());
    }

    public function test()
    {
        $mid = new Middleware();
        $mid->middleware('Auth');
        $mid->run();
    }
}