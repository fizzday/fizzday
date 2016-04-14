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