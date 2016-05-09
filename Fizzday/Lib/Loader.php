<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/5/6
 * Time: 17:12
 */

namespace Fizzday\Lib;
/**
 * 文件加载类
 *  Load::view('path.name');
 *  Load::config('path.name');
 *  Load::file('/path/to/file');
 */
class Loader
{

    public static function __callStatic($name, $arguments)
    {
        if (empty($arguments)) die('view is not given');
        switch ($name) {
            case 'view': $const = VIEW_PATH; break;
            case 'config': $const = CONFIG_PATH; break;
            default: $const = ''; break;
        }

        $file =$const.str_replace('.', '/', $arguments[0]).".php";

        require $file;
    }


}