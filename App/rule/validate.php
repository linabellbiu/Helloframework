<?php
//验证规则

\Core\Validate::getinstance()
    ->rulesCookie([
        'AEX_md5' => 'string|len:32',
        'AEX_id' => 'int',
    ])
    ->paramError([
        'isNull' => ['zh' => '输入的不能为空', 'en' => 'input can not null']
    ]);
