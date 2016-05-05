<?php
/**
 * @Author: anchen
 * @Date:   2016-04-14 14:23:21
 * @Last Modified by:   anchen
 * @Last Modified time: 2016-04-14 18:33:17
 */

$config = [];

// 是否启用模板
$config['view'] = 'on';

// 自动加载类
$config['autoload'] = array(
    'view'  => 'Fizzday\View\View',
    'DB'    => 'Fizzday\Database\DB'
);













return $config;