<?php
/**
 * Created by PhpStorm.
 * User: wangxudong is good body
 * Date: 2018/9/25
 * Time: 9:54
 */

use Core\Http\Cookie;

/**
 * conf
 */
return
    [
        'ERR_DEBUG' => APP_DEBUG,
        'LANGUAGE' => DEFAULT_LANGUAGE,
        'templet_cache_path' => __VIEW__ . '/cache/',
        'template_path' => APP_PATH . '/view/'
    ];