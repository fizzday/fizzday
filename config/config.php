<?php
$config = [];

$config['path'] = [
    'controller' => 'app/Controllers',
    'model' => 'app/Models',
    'view' => 'app/Views',
    'cache' => 'cache',
    'route' => 'routes'
];

$config['namespace'] = [
    'controller'    => '\\App\\Controllers'
];

/**
 * 开关
 */
$config['switch'] = [
    'view'  => 'on', // 是否启用 view 层. off: 不启用, on: 启用
    'log'   => 'off', // 是否启用 log. off: 不启用, on: 启用
];

$config['class_alias'] = [

];

return $config;
