<?php
use Core\Http\Cookie;

Cookie::cookie('language') ? Cookie::cookie('language') : Cookie::cookie('language', config('language'));





//加载应用
return [
    //应用配置
    "conf"=>[
        'index.conf',
        'db.conf',
    ],


    //加载语言包
    "language"=>[
        'zh',      //中文
        'en',      //英文
    ],
];