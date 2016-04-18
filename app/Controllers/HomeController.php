<?php

class HomeController
{

    public function index()
    {
//        $user = new User();
//        v($user);
//        $user->name = 'fizzday3';
//        $user->save();
//        User::Update(['name', 'fizzday'], ['id',2]);
        $a = db::tableExists('user');
        v($a);
        v(db::lastSql());

        $bb = db::transaction(function(){
            User::update(['age'=>25], ['id'=>16]);
            User::update(['age'=>26], ['id'=>17]);
        });
        v($bb);
        $user = User::fields()->where(['name', 'like', 'fiz%'])->order('id desc')->limit(3)->get();
        v($user);
        View::make('test')->with('name', $user[0]->name)->withAge($user[0]->age);
    }

    public function test()
    {
        echo '前台模块的test方法';
    }
}
