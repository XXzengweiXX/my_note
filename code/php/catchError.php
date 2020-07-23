<?php

try {
//    $a=1;
//    $b=0;
    // 无法通过catch捕获
//    $res = $a/$b;
    //可以通过catch捕获,PHP内核抛出错误的专用类型, 如类不存在, 函数不存在, 函数参数错误
    test();
}catch (Error $e){
    echo 'error msg:'.$e->getMessage();
}