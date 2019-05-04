<?php
/**
 * Created by PhpStorm.
 * User: wangxdong
 * Date: 2019/4/21
 * Time: 12:33
 */

namespace View;

use View\Engine\EngineInterface;

class HelloEngine implements EngineInterface
{

    public function get($path, array $data = [])
    {
        return $this->evaluatePath($path, $data);
    }


    protected function evaluatePath($__path, $__data)
    {
        ob_start();

        extract($__data, EXTR_SKIP);

        include $__path;

        return ltrim(ob_get_clean());
    }

}