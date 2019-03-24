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
    public function __construct($name, $connect = '', $force = false)
    {
        parent::__construct($name, $connect, $force);
    }

    function echos(){
        echo "model";
    }
}