<?php return [
    'plugin' => [
        'name' => 'Webhooks',
        'description' => 'webhooks Support Coding|github'
    ],
    'setting'=>[
        'title'=>'Webhooks Manager',
        'desc'=>'Webhooks Manager Support Coding|github ',
        'key'=>'Webhook key',
        'key_comment'=>'Git callback key [recommend]',
        'enabled'=>'enabled',
        'enabled_comment'=>'enable/disable webhooks',
        'link'=>'URL',
        'content_type'=>'Content type',
    ],
    'widget' => [
        'command'=>'command tools',
        'command_placeholder'=>'shell command',
        'exec'=>'exec',
        'exec_confirm'=>'confirm do this command ',
        'title' => 'Git tools - be careful',
        'desc' => 'Git tool for manager version',
        'pull'=>'pull',
        'pull_confirm'=>'confirm do git pull',
        'reset'=>'reset',
        'reset_confirm'=>'confirm do git reset',
        'hash'=>'Hash version',
        'hash_placeholder'=>'input version'
    ],
];