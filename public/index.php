<?php
/**
 * @Author: fizzday
 * @Date:   2016-04-13 09:07:49
 * @Last Modified by:   anchen
 * @Last Modified time: 2016-04-14 14:22:51
 */
// 公共目录
define("PUBLIC_PATH", __DIR__."/");

// 根目录
define("BASE_PATH", PUBLIC_PATH."../");

// 配置文件目录
define("CONFIG_PATH", BASE_PATH."config/");

// 模板目录
define("VIEW_PATH", BASE_PATH."app/Views/");

// 加载composer自动化文件
require BASE_PATH."vendor/autoload.php";

// 加载系统总配置文件
$config = require BASE_PATH."config/config.php";

// 加载路由文件
require BASE_PATH."app/routes.php";

// 根据配置文件, 判断是否启用模板
if ($config['view'] == 'on') Fizzday\View\FizzView::run();

