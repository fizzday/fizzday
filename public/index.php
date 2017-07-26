<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE, PATCH, OPTIONS, HEAD');

define('ROOT_PATH', __DIR__ . '/');

require ROOT_PATH . '../bootstrap/boot.php';
