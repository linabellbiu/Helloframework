<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/10/10
 * Time: 17:54
 */
namespace Core;

/**
 * Class Custom
 * @package Core
 */
class Custom
{

    /**
     * @var
     */
    private static $message = [];


    /**
     * php >= 5.6
     * 批量设置语言
     * @param array ...$messages
     */
    /*
    public function batchSetCustom(...$messages)
    {
        if (is_array($messages) && !empty($messages)) {
            foreach ($messages as $lang => $arr) {
                foreach ($arr as $attribute => $mes) {
                    $this->message[$lang][$attribute] = $mes;
                }
            }
        }
        return;
    }*/

    /**
     * @param $arr
     * @return null
     */
    static function setCustom($arr)
    {
        if (empty($lang) || !is_array($arr)) {
            return null;
        }
        foreach ($arr as $attribute => $mes)
        {
            self::$message[$attribute] = $mes;
        }
        return null;
    }

    /**
     * 获取语言包
     * @param $attribute
     * @return null
     */
    static public function getCustom($attribute)
    {
        if (empty($attribute) && !is_string($attribute))
        {
            return null;
        }
        return self::$message[$attribute];
    }
}