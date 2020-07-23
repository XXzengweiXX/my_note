<?php

$arr = ['a'=>"ab",'2'=>"abdf",'c'=>"dfd",'aq'=>"gffdg",'jk'=>50,'zsa'=>"dd",];
$nums = [1,-9,34,45,12,0,1];

//sort($arr);
//rsort($arr);

//ksort($arr);

//usort($nums,'cmp');
//var_dump($nums);


function cmp($a,$b){
    $sub = $a-$b;
    if($sub==0){
        return 0;
    }
    return $sub>0?1:-1;
}


$myArr = [
  [
      'name'=>'jhon',
      'age'=>12,
      'sex'=>'m',
  ],
    [
        'name'=>'tom',
        'age'=>19,
        'sex'=>'m',
    ],
    [
        'name'=>'july',
        'age'=>15,
        'sex'=>'f',
    ],
    [
        'name'=>'jack',
        'age'=>5,
        'sex'=>'m',
    ],
];

//var_dump(array_column($myArr,'name'));

array_multisort(array_column($myArr,'age'),SORT_ASC,$myArr);
var_dump($myArr);

//var_dump(mulSort($myArr,'sex','desc'));


function mulSort($arr=[],$key,$orderBy='asc'){
    if(empty($arr)){
        return $arr;
    }

    $nArr = $arr;
    $callable = function ($a1,$a2) use ($key,$orderBy) {
        if(!isset($a1[$key])||!isset($a2[$key])){
            throw new Exception("array no key:{$key}");
        }
        $res = strcmp($a1[$key],$a2[$key]);
        if($orderBy!='asc'){
            $res*=-1;
        }
        return $res;
    };
    usort($nArr,$callable);
    return $nArr;
}