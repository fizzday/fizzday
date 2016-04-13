<?php
/**
 * @Author: anchen
 * @Date:   2016-04-13 09:07:49
 * @Last Modified by:   anchen
 * @Last Modified time: 2016-04-13 14:48:19
 */

define("PUBLIC_PATH", __DIR__."/");

define("BASE_PATH", PUBLIC_PATH."../");

require BASE_PATH."vendor/autoload.php";

require BASE_PATH."app/routes.php";
