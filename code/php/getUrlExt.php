<?php
// 从一个标准url里取出文件的扩展名，例如:  http://www.qq.com.cn/abc/de/fg.php?id=1需要取出php或.php
function getExt($url){
  //方法1 pathinfo
  // $arr = pathinfo($url);
  // $extension = $arr['extension'];
  // $extensionArr = explode('?',$extension);
  // return $extensionArr[0];

  //方法2 parse_url
  $arr = parse_url($url);
  $path = $arr['path'];
  $ext = strrchr($path,'.');//获取最有一个.后面的部分
  return $ext;
};

$url = "http://www.qq.com.cn/abc/de/fg.php?id=1";
$res = getExt($url);
var_dump($res);