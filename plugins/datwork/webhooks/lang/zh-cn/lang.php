<?php return [
    'plugin' => [
        'name' => 'Webhooks',
        'description' => 'webhooks 支持 Coding|github'
    ],
    'setting'=>[
        'title'=>'Webhooks 管理',
        'desc'=>'管理 Webhooks 支持 Coding|github ',
        'key'=>'Webhook 密匙',
        'key_comment'=>'Git 资源上设置的回调密钥 [强烈建议]',
        'enabled'=>'启用',
        'enabled_comment'=>'点击启用/禁用 webhooks',
        'link'=>'URL',
        'content_type'=>'Content type',
    ],
    'widget' => [
        'command'=>'命令行工具',
        'command_placeholder'=>'shell命令',
        'exec'=>'执行',
        'exec_confirm'=>'确认执行代码',
        'title' => 'Git 工具 - 危险操作',
        'desc' => 'Git tool for manager version',
        'pull'=>'pull',
        'pull_confirm'=>'确认执行 git pull',
        'reset'=>'reset',
        'reset_confirm'=>'确认执行 git reset',
        'hash'=>'Hash 版本号',
        'hash_placeholder'=>'请输入版本号'
    ],
];