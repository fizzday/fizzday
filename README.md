# fizzday
自由, 快捷, 高效, 灵活... 没错, 这就是fizzday, 一个小巧玲珑却又相当强悍的框架, 你值得拥有
## 优雅的路由
```
Rout::get('test', 'HomeController@index');

Rout::post('test', function() {
    echo 'test post route';
});

Rout::group('admin', function() {
    Rout::get('test', 'Admin\AdminController@test');
});
```

## 小巧强悍的ORM(兼容常规模式,AR模式,链式操作)
```
// 链式操作
db::table('user')->where(['id', '=', 1])->get();
db::getAll('user', ['id','>',1]);

// Active Record 模式
User::find(1);
User::where(['name','like','fizz%'])->getAll();

// 原生语句(查询)
db::query("SELECT * FROM `user` where id>?", [1]);
// 或者
db::query("SELECT * FROM `user` where id>1");

// 原生语句(非查询)
db::execute("UPDATE `user` SET `age`=?", [25]);
// 或者
db::execute("UPDATE `user` SET `age`=25");

// 长查询,支持多种模式(limit(offset, limit))
db::table('user')->fields('id','name','age')->where(['id','>',1])->group('age')->order('`age` asc, id desc')->limit(10);
```
说明:
1. 由于涉及到灵活和自由扩展性, 暂时没有加入联表等复杂查询的封装, 可以使用原生查询来完成这些工作.
2. 建议使用pdo方式, 提高数据操作的安全性

## 清晰的模板分配
```
view::make('home.test')
    ->with('name', 'fizzday')
    ->with('age', 26);
```

## 版本说明
> v0.10
composer init, 初始化框架目录


> v0.11
route complate, 路由完善
