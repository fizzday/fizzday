<?php
namespace App\Controllers;

use Fizzday\FizzJWT\Auth;

class AuthenticateController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        try {
            $auth = Auth::check();

            if (! $auth) die(jsonReturn('认证失败', 401));
        } catch (\Exception $e) {
            if ($e->getMessage() == 'Expired token') die(jsonReturn('身份过期,请重新登录', 401));
            die(jsonReturn($e->getMessage(), 1));
        }
    }
}
