<?php
// 项目的源码根目录
define('BASE_PATH', __DIR__ . '/../');
// 配置目录
define('CONF_PATH', BASE_PATH . 'config/');
// 请求composer入口文件
require BASE_PATH . 'vendor/autoload.php';
// 路由目录
define('ROUTE_PATH', BASE_PATH.config('config.path.route').'/');
// 缓存目录
define('CACHE_PATH', BASE_PATH.config('config.path.cache').'/');

// 取别名, 这样就不需要在 routes/routes.php 中 use FizzRoute 了
class_alias('\\Fizzday\\FizzRoute\\Route', 'Route');
// 引入路由
require ROUTE_PATH . 'route.php';
// 驱动路由
Route::dispatch();

// 驱动DB
//\Fizzday\Database\DB::run();

// 模板启用
if (config('config.switch.view') == 'on') {
    // 模板目录
    define('VIEW_PATH', BASE_PATH.config('config.path.view').'/');

    class_alias('\\Fizzday\\FizzView\\View', 'View');
    // 驱动模板
    View::run();
}