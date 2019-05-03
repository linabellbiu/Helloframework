<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/9/25
 * Time: 9:57
 */

namespace App\controller\index;

use Core\Controller;
use Core\Http\Request;
use App\model\index\IndexModel;
use Factory\Factory;
use View\View;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
//        $test = new IndexModel("wang", config('db1'));
//        $test->exec();
        $arr['pagetitle'] = '测试';
        $arr['test'] = '测试内容';
       view('index',$arr);
    }
}