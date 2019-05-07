<?php

use \Core\RouteService;
use \Core\Validate;

Validate::getinstance()->Cookie(['language' => 'string'])->bandingError(
    ['language|string' => 'language_error']
);

RouteService::get('IndexController@index', '/a', [
    'mail' => 'mail',
])->bandingError([
    'mail|null' => ['zh' => '输入不能为空', 'en' => 'english error'],
    'mail|mail' => ['zh' => '不是正确的邮箱', 'en' => 'this is not e-mail'],
]);

RouteService::get('IndexController@login', '/login');

RouteService::post('IndexController@postLogin', '/login', [
    'mail' => 'mail',
    'pwd' => 'pwd',
    'ggkey' => 'int|null',
])->bandingError([
    'mail|null' => ['zh' => '输入不能为空', 'en' => 'english error'],
    'mail|mail' => ['zh' => '不是正确的邮箱', 'en' => 'this is not e-mail'],
    'pwd|null' => ['zh' => '输入不能为空', 'en' => 'english error'],
    'pwd|pwd' => ['zh' => '输入不能为空', 'en' => 'english error'],
    'ggkey|int' => ['zh' => '输入不能为空', 'en' => 'english error'],
]);