<?php

use \Core\RouteService;
use \Core\Validate;

//Validate::getinstance()->Cookie(['language' => 'string'])->bandingError(
//    ['language|string' => 'language_error']
//);

RouteService::get('IndexController@index', '/', function () {
    //这个闭包是在控制器之后
//   view('welcome','test','call');
    \Core\Http\Cookie::bindingParam(['language' => 'string']);
})->bindingParam([
    'mail' => 'mail',
    'pwd' => 'pwd',
    'ggkey' => 'int',
]);
    /*->bindingError([
    'mail|null' => ['zh' => '输入不能为空', 'en' => 'english error'],
    'mail|mail' => ['zh' => '不是正确的邮箱', 'en' => 'this is not e-mail'],
    'pwd|null' => ['zh' => '输入不能为空', 'en' => 'english error'],
    'pwd|pwd' => ['zh' => '输入不能为空', 'en' => 'english error'],
    'ggkey|int' => ['zh' => '输入不能为空', 'en' => 'english error'],
]);*/

//RouteService::get('IndexController@index', '/index');
//RouteService::get('IndexController@index', '/index.php');
//
RouteService::get('IndexController@login', '/login');
RouteService::get('IndexController@test', '/test');
//
RouteService::post('IndexController@postLogin', '/login');
//    ->bandingError([
//    'mail|null' => ['zh' => '输入不能为空', 'en' => 'english error'],
//    'mail|mail' => ['zh' => '不是正确的邮箱', 'en' => 'this is not e-mail'],
//    'pwd|null' => ['zh' => '输入不能为空', 'en' => 'english error'],
//    'pwd|pwd' => ['zh' => '输入不能为空', 'en' => 'english error'],
//    'ggkey|int' => ['zh' => '输入不能为空', 'en' => 'english error'],
//]);