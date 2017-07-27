<?php
/**
 * Created by PhpStorm.
 * User: fizz
 * Date: 2017/7/2
 * Time: 19:29
 */

namespace App\Models;


use Fizzday\FizzDB\DB;

class User
{
    public static function getInfo($mobile)
    {
        $info = DB::table('users')->fields('money')->where('mobile', $mobile)->first();

        if (!$info) return failReturn();

        $return = [];
        $return['mobile'] = $mobile;
        $return['money'] = $info->money;
        $return['task'] = DB::table('user_task')->where('mobile', $mobile)->where('status', 3)->count();

        return successReturn($return);
    }
}