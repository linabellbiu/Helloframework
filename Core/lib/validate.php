<?php

use \Core\Validate;

Validate::getinstance()
    ->bandingError([
        'SYS_ROUTE_REQ_IS_NULL' => ['input can not null'],
        'SYS_REQ_METHOD_ERROR' => ['request is not err'],
    ]);

//警告:不建议删除,不能修改
Validate::getinstance()->Data(
    [
        'c' => 'controller|null',
        'm' => 'mothed|null'
    ])->Cookie(['language' => 'string']);