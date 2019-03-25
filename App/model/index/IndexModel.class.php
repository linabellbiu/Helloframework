<?php
/**
 * Created by PhpStorm.
 * User: wangxdong
 * Date: 2019/3/5
 * Time: 20:59
 */

namespace App\model\index;

use \Core\Model;

class IndexModel extends Model
{
    public function __construct($name, $connect, $force = false)
    {
        parent::__construct($name, $connect, $force);
    }

    function exec()
    {
        $data['balance'] = 10000;
        $result = $this->where("(coin=:coin1 or coin=:coin2) and user_id =:user_id", ['btc', 'eth', 1])->save($data);
        var_dump($this->error());
        var_dump($result);
    }
}