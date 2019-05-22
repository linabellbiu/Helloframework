<?php
/**
 * Created by PhpStorm.
 * User: wangxdong
 * Date: 2019/5/4
 * Time: 13:20
 */

namespace Core;

use \Core\Http\Request;

class Heplers
{

    /**
     * @param null $url
     * @return array
     */
    public static function originalUrlControllerAndMothed($url = null)
    {
        $urlarr = explode('?', $url);

        $c = INDEX_CONTROLLER;
        $m = INDEX_METHOD;

        if (empty($urlarr[1])) {
            return [$c, $m];
        }

        $request = Request::request();

        if (isset($request['c'])) {
            $c = $request['c'] . CONTEROLLER_POSTFIX;
        }
        if (isset($request['m'])) {
            $m = $request['m'];
        }

        return [$c, $m];
    }

    public static function loadFile($file)
    {
        $files = null;
        try {
            if (($files = load_file($file)) === false) {
                throw new Error($file . '没有找到');
            }
        } catch (Error $e) {
            $e->errorMessage();
        }
        return $files;
    }

    public static function defaultRouteName()
    {
        return INDEX_CONTROLLER . CONTROLLER_METHOD_DELIMIT . INDEX_METHOD;
    }


}