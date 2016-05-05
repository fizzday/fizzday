<?php

// 加载系统总配置文件
require BASE_PATH."config/config.php";

/**
 * 检查是否开启调试模式
 */
define('ENVIRONMENT', isset($config['debug']) ? $config['debug'] : 'on');

switch (ENVIRONMENT)
{
    case 'on':
        error_reporting(-1);
        ini_set('display_errors', 1);
        break;

    case 'off':
        ini_set('display_errors', 0);
        if (version_compare(PHP_VERSION, '5.3', '>='))
        {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        }
        else
        {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        }
        break;

    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1); // EXIT_ERROR
}




// 加载composer自动化文件
require BASE_PATH."vendor/autoload.php";


/**
 * 检查是否启用db操作
 */

if ($config['db'] == on) {

    use Illuminate\Database\Capsule\Manager as Capsule;

    require BASE_PATH.'/config/database.php'

    // Eloquent ORM
    $capsule = new Capsule;

    $capsule->addConnection($db[$config['dbDefault']]);

    $capsule->setAsGlobal();

    $capsule->bootEloquent();

}


/**
 * 检查是否有类取别名
 */

if (!empty($config['alias'])) {

    foreach ($config['alias'] as $k => $v) {

        class_alias($v, $k);

    }

}



// 加载路由文件
require BASE_PATH."app/routes.php";

// 根据配置文件, 判断是否启用模板, 并最终渲染模板
if ($config['view'] == 'on') Fizzday\View\FizzView::run();
