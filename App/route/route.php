<?php

\Core\RouteService::get('IndexController@index', '/', [
    'm' => 'string|mail',
])->paramError([
    'm' => ['zh'=>'中文错误','en'=>'english error'],
]);

