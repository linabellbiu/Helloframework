<?php
/**
 * Created by PhpStorm.
 * User: wangxdong
 * Date: 2019/4/20
 * Time: 12:29
 */

namespace View\Support;

class Str{

    public static function contains($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }

}