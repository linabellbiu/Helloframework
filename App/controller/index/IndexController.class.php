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

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $test = new IndexModel("test.wang", config('db1'));
        $dataList = [
            'name'=>'go2', 'page'=>1,
        ];

       echo  $test->add($dataList);

        echo $test->error();
        $result = $test->select("select * from test.wang");


        var_dump($result);
    }
}