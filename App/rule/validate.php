<?php
//验证规则

\Core\Validate::getinstance()
    ->bandingError([
        'isNull' => ['zh' => '输入的不能为空', 'en' => 'input can not null'],
        'reqErr'=>['zh'=>'请求方式不正确','en'=>'request is not err'],
    ]);



