<?php
/**
 * nullable 可选字段，可以不填，填了必须验证
 */



\Core\RouteService::get('IndexController@index', '/', [
    'm' => 'mail|nullable',
])->bandingError([
    'm_string' => ['zh'=>'中文错误','en'=>'english error'],
    'm_mail' => ['zh'=>'不是正确的邮箱','en'=>'this is not e-mail'],
]);
