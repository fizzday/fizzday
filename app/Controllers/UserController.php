<?php
namespace App\Controllers;

use App\Models\User;
use Fizzday\FizzJWT\Auth;

class UserController extends AuthenticateController
{
    public function getinfo()
    {
        $mobile = Auth::user('aud');

        echo json_encode(User::getInfo($mobile));
    }
}