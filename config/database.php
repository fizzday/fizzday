<?php

$config['db']['default'] = [
    'driver'    => 'pdo',
    'host'      => '127.0.0.1',
    'dbname'    => 'fizzday',
    'username'  => 'fizz',
    'password'  => '123456',
    'charset'   => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix'    => ''
];


$config['db']['default2'] = [
    'driver'    => 'pdo',
    'host'      => '203.88.163.89',
    'dbname'    => 'fizzday',
    'username'  => 'fizz',
    'password'  => '123456',
    'charset'   => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix'    => ''
];

return $config;