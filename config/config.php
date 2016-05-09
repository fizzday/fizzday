<?php
/**
 * @Author: anchen
 * @Date:   2016-04-14 14:23:21
 * @Last Modified by:   anchen
 * @Last Modified time: 2016-04-14 18:33:17
 */

$config = [];

/**
 * 是否开启调试模式:
 * on : 启用调试模式
 * off : 关闭调试模式
 * other: 其他(可以是任意的字符), 中断执行
 */
$config['debug'] = 'on';

/**
 * 默认数据库的连接
 */
$config['dbDefault'] = 'default';

/**
 * 是否启用模板
 * on : 启用
 * other : 不起用
 */
$config['view'] = 'on';

/**
 * 是否启用db类
 * on : 启用
 * other : 不起用
 */
$config['db'] = 'on';

/**
 * 自动加载类
 */
$config['autoload'] = array(
//    'view'  => 'Fizzday\View\View',
//    'DB'    => 'Fizzday\Database\DB'
);

/**
 * 类取别名
 */
$config['alias'] = array(
    'view'      => 'Fizzday\View\FizzView',
    'R'         => 'Fizzday\Routing\FizzRoute',
    'DB'        => 'Illuminate\Database\Capsule\Manager',
    'Eloquent'  => 'Illuminate\Database\Eloquent\Model'
);




