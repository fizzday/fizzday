<?php

$config['db_default'] = 'mysql';

$config['mysql'] = [
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'port'      => 3306,
    'database'  => 'fizz',
    'username'  => 'fizz',
    'password'  => 'root',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => 'fizz_',
//    'read'      => [
//        'host'  => '127.0.0.1'
//    ],
//    'write'      => [
//        'host'  => '127.0.0.1',
//        'database'  => 'test2',
//    ]
];

$config['mysql2'] = [
    'driver'    => 'mysql',
    'host'      => '192.168.200.248',
    'port'      => 3306,
    'database'  => 'test',
    'username'  => 'test',
    'password'  => 'test',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => 'fd_',
    'read'      => [
        'host'      => '192.168.200.248',
        'database'  => 'test',
    ],
    'write'      => [
        'host'      => '192.168.200.249',
        'database'  => 'test',
    ]
];

return $config;