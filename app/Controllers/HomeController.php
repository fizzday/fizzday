<?php
namespace App\Controllers;

use Fizzday\FizzDB\DB;
use Fizzday\FizzJWT\Auth;
use Fizzday\FizzJWT\FizzJWT;
use Fizzday\FizzView\View;
use Fizzday\Requests\Request;

class HomeController extends BaseController
{
    public function index()
    {
        show_msg('welcome home page');
    }

    public function test_xss(Request $request)
    {
        $res = $request->all();
//        $res = $request->all();

        dump($res);
//        echo $_SERVER['REQUEST_METHOD'];
    }

    public function test_view(Request $request)
    {
//        $all = Request::input('a');
        View::make('test_view')->withA($request->a);

//        dd($request->a);
    }

    public function test_api()
    {
        $payLoad = [
            'uid'=>223
        ];
        $key = config('jwt.key');

        $token = FizzJWT::encode(Auth::getPayload('', 123), $key);
//        echo jsonReturn($token);
        die($token);

        try {
            echo jsonReturn(FizzJWT::decode($token, $key));


        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function test_db()
    {
        $db = new DB();

        $res = $db->table('tasks')->where('id', '>', 2)->limit(2)->get();
//        echo json_encode($res);
        $res = $db->table('users')->get();
        echo json_encode($res);
//        $res = DB::table('test')->get();
//        DB::table('test')->chunk(2, function ($query) {
//            dump($query);
//        });

//        dd($db->lastSql());
    }

    public function test_trans()
    {
//        DB::transaction(function () {
//            DB::table('money_log')->insert(['mobile'=>'123']);
//            $res = DB::table('user_log')->insert(['mobiles'=>'123']);
//            var_dump($res);die;
//        });
//        $db = new db();
        DB::beginTransaction();
//        $db->table('money_log')->where('id', 8)->increment('money');
//        $res = $db->table('user_logs')->insert(['mobile'=>'123']);
        DB::table('money_log')->where('id', 8)->increment('money');
        $res = DB::table('user_logs')->insert(['mobile'=>'123']);
        var_dump($res);
        if (!$res) DB::rollBack();
//        var_dump($db->lastSql());die;
        else DB::commit();

        echo 'finish';
    }
}