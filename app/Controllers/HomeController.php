<?php
//use Illuminate\Database\Capsule\Manager as db;
class HomeController extends BaseController
{

    public function index()
    {
        $user = User::where('name', 'like', 'fiz%')->offset(4)->first();
        
        View::make()->with('name', $user->name)->withAge($user->age);
    }

    public function test()
    {
        echo '前台模块的test方法';
    }
}
