<?php

/**
 * 是否以某个字符串开头
 * @param  string $word 原生字符串, 如: withName
 * @param  string $str  标识字符串, 如: with
 * @return boolean      返回判断结果
 */
function start_with($word, $str)
{
    if (!empty($word) && !empty($str)) {
        $len = strlen(trim($str));
        if (substr($word, 0, $len) == $str) return true;
    }
    return false;
}

/**
 * 载入文件
 * @param string $name
 * @param string $path
 * @return mixed
 */
function load($name='database', $path='')
{
    if (empty($path)) {
        $path = 'BASE_PATH'.'config/';
    }
    return require rtrim($path, '/').'/'.$name.'.php';
}

/**
 * 给表名或字段名添加反引号
 * @param string $tab
 * @return string
 */
function addQuotes($tab = '')
{
    if ($tab) return '`'.$tab.'`';
}

/**
 * 格式化打印, 并终止
 */
function d($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    exit;
}
/**
 * 格式化打印, 不终止
 */
function v($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}