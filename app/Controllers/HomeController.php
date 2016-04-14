<?php

class HomeController
{

    public function index()
    {
        return View::make('test')->with('name', 'fizzday')->withAge(26);
    }

    public function test()
    {
        echo '前台模块的test方法';
    }
}
