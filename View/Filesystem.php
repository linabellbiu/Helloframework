<?php
/**
 * Created by PhpStorm.
 * User: wangxdong
 * Date: 2019/4/16
 * Time: 20:59
 */

namespace View;

class Filesystem
{
    const HINT_PATH_DELIMITER = '.';

    public static function isFileExist($file)
    {
        if (!file_exists($file)) {
            return false;
        }
        return true;
    }

    public static function normalize($name)
    {
        return str_replace('.', '/', $name);
    }
}