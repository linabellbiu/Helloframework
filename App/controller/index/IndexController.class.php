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
        $test = new IndexModel("t_user_balance_new", config('db_bitcc_money'));

        $data['balance'] = 10000;
        $result = $test->where("(coin=:coin1 or coin=:coin2) and user_id =:user_id", ['btc', 'eth', 1])->save($data,true);
        var_dump($test->error());
        var_dump($result);
    }
}