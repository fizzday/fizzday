<?php
/**
 * Created by PhpStorm.
 * User: fizz
 * Date: 2016/4/25
 * Time: 20:57
 */

class BaseController
{
    public function __construct()
    {
        // 方便直接使用 View 加载模板, db 操作数据库, 不用输入完整的命名空间路径
//        class_alias('Fizzday\View\FizzView', 'View');
//        class_alias('Fizzday\Database\db', 'db');
    }
}