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
        $data['page'] = 10000;

        $result = $this->fetchSql()->where("page = :page and work = :work",1,'it')->find();

        var_dump($this->error());
        var_dump($result);
    }
}