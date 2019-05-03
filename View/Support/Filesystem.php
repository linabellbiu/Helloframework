<?php
/**
 * Created by PhpStorm.
 * User: wangxdong
 * Date: 2019/4/16
 * Time: 20:59
 */

namespace View\Support;

use Exception;

class Filesystem
{
    const HINT_PATH_DELIMITER = '.';

    public static function fileExist($file)
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

    public function get($path)
    {
        if (is_file($path)) {
            return file_get_contents($path);
        }

        throw new Exception("File does not exist at path {$path}");
    }

    public function put($path, $contents, $lock = false)
    {
        file_put_contents($path, $contents, $lock ? $lock : 0);
    }

    public function lastModified($path)
    {
        return filemtime($path);
    }
}