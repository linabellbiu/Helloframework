<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/6
 * Time: 16:19
 */

namespace Core\Hello\Build;


use Core\Hello\LoadInterFace;

/**
 * 加载类
 * 所有实现LoadInterFace的子类通过这个类构建到系统中
 * Class Load
 * @package Core\Hello\Build
 */
class Load
{
    private $loadInterFace;

    /**
     * Load constructor.
     * @param LoadInterFace $loadInterFace
     */
    public function __construct(LoadInterFace $loadInterFace)
    {
        $this->loadInterFace = $loadInterFace;
    }


    /**
     * 调用加载接口的load方法
     */
    public function LoadComponent()
    {
        $this->loadInterFace->load();
    }
}