<?php
/**
 * nullable 可选字段，可以不填，填了必须验证
 */

\Core\RouteService::get('index/IndexController@index', '/', [
    'm' => 'mail|nullable',
])->rulesCookie([
    'language'=>'mail'
])->bandingError([
    'm|string' => ['zh'=>'中文错误','en'=>'english error'],
    'm|mail' => ['zh'=>'不是正确的邮箱','en'=>'this is not e-mail'],
    'language|mail' => ['zh'=>'不是正确的邮箱','en'=>'this is not e-mail'],
]);