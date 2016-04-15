<?php

class HomeController
{

    public function index()
    {
        $user = new User();
        v($user);
        $user->name = 'fizzday3';
        $user->save();
//        User::Update(['name', 'fizzday'], ['id',2]);
        v(User::lastSql());
        $user = User::fields()->where(['name', 'like', 'fiz%'])->order('id asc')->limit(29)->get();
        v($user);
        View::make('test')->with('name', $user[0]->name)->withAge($user[0]->age);
    }

    public function test()
    {
        echo '前台模块的test方法';
    }
}
