<?php
namespace App\Controllers;

use Fizzday\Requests\Request;

class BaseController
{
    public function __construct()
    {
        // 是否记录请求log
        if (config('config.switch.log')=='on') {
            // 记录下log
            $log = [];
            $log['token'] = Request::header('token');
            $log['param'] = Request::all();

            file_set('log/'.date('Ymd').'.txt', $_SERVER['REQUEST_URI']."    ".date("Y-m-d H:i:s").PHP_EOL.json_encode($log).PHP_EOL);
        }
    }
}
