<?php
/**
 * Created by PhpStorm.
 * User: fizz
 * Date: 2017/7/3
 * Time: 21:06
 */

namespace App\Models;


use Fizzday\FizzDB\DB;
use Fizzday\FizzJWT\Auth;
use Fizzday\FizzJWT\FizzJWT;

class Nologin
{
    public static function login($request, $reg=false)
    {
        if (!($request->mobile) || !($request->password)) {
            return failReturn('手机和密码不能为空');
        }
        // 检测手机号是否已经注册
        $pwd_md5 = md5($request->input('password'));

        $userInfo = DB::table('users')->where('mobile', $request->mobile)->first();

        if (!$userInfo) {
            if ($reg) {
                $data = [
                    'mobile'=>$request->mobile,
                    'password'=>$pwd_md5,
                    'ip'=>$request->ip
                ];

                if (DB::table('users')->insert($data)) {
                    $return = User::getInfo($request->mobile);
                    if ($return['status'] != 0) return failReturn();

                    $return['data']['token'] = FizzJWT::encode(Auth::getPayload('', $request->mobile), config('jwt.key'));
                    return $return;
                }
            }
        } elseif($reg){
            return failReturn('账号已存在', 1);
        } else {
            if ($pwd_md5 == $userInfo->password) {
                $return = User::getInfo($request->mobile);
                if ($return['status'] != 0) return failReturn();

                $return['data']['token'] = FizzJWT::encode(Auth::getPayload('', $request->mobile), config('jwt.key'));
                return $return;
            }
        }

        return failReturn();
    }
}