<?php
namespace Fizzday\View;

class FizzView
{
    // 模板文件路径
    public static $viewPath = [];
    // 分配的变量数组
    public static $data = [];
    // 是否返回解析后的文本(发邮件等情况下会使用到), 默认false
    public static $return = false;

    /**
     * 分配模板
     * @param  sting  $viewName 模板名字, 如: admin.index或者admin/index
     * @param  boolean $type     是否返回为文本
     * @return mixed            输出或返回文本
     */
    public static function make($viewName = null, $type=false)
    {
        if (!defined('VIEW_PATH')) die("未定义 VIEW_PATH 常量");
        if (!$viewName) {
            if (!class_exists("\R")) die('没有保存 autoView 数据对应的类 \R ');
            // 检查是否存在 $autoView 变量
            if (!array_key_exists('autoView', get_class_vars("\R"))) die('获取不到保存自动模板的变量, 检查 \R::$autoView 是否存在');

            $autoView = \R::$autoView;
            $viewName = str_replace('\\', '.', str_replace('controller', '', strtolower($autoView['class']))).'.'.$autoView['function'];
        }
        $viewFilePath = VIEW_PATH . str_replace('.', '/', $viewName) . '.php';

        if (is_file($viewFilePath)) static::$viewPath[] = $viewFilePath;
        else die("View file does not exist!");

        if ($type) static::$return = true;
        return new static;
    }

    /**
     * 分配变量
     * @param  sting $key   变量key
     * @param  string $value 变量值
     * @return obj          返回链式操作对象
     */
    public static function with($key, $value = null)
    {
        if (is_array($key) && !empty($key)) {
            foreach ($key as $k => $v) {
                static::$data[$k] = $v;
            }
        } else static::$data[$key] = $value;

        return new static;
    }

    /**
     * 捕获未定义的方法
     * @param  sting $method     变量key和with组合
     * @param  mixed $parameters 变量值
     * @return obj             [description]
     */
    public function __call($method, $parameters)
    {
        if (start_with($method, 'with')) static::with(lcfirst(substr_replace($method, '', 0, 4)) , $parameters[0]);
        else die("Function [$method] does not exist!");
        return new static;
    }

    /**
     * 渲染变量到模板
     * @return mixed 最终页面
     */
    public static function run()
    {
        // 获取模板文件
        $viewPath = static::$viewPath;
        $data = static::$data;
        $return = static::$return;

        if ($viewPath) {
            // 分配变量
            extract($data);               // 抽取数组中的变量
            if (ob_get_contents()) ob_end_clean ();              //关闭顶层的输出缓冲区内容
            ob_start ();                  // 开始一个新的缓冲区

            foreach ($viewPath as $v) {
                require $v;                //加载解析后的文件
            }

            $content = ob_get_contents ();// 获得缓冲区的内容
            if (ob_get_contents()) ob_end_clean ();              // 关闭缓冲区
            ob_start();                   //开始新的缓冲区，给后面的程序用

            // 重置变量
            static::reset();

            // 处理返回
            if ($return) return $content;       // 返回文本。
            else echo $content;
        }

    }

    /**
     * 重置模板输出信息
     * @return [type] [description]
     */
    private static function reset()
    {
        static::$viewPath = [];
        static::$data = [];
        static::$return = false;
    }
}

