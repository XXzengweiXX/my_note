<?php
//确保多个进程同时写入同一个文件成功

function writeFile($filename,$data){
  $fp = fopen($filename,'a');
  while(!flock($fp,LOCK_EX)){
    usleep(mt_rand(100,1000));
  }
  fwrite($fp,$data);
  flock($fp,LOCK_UN);
  fclose($fp);
}

$str="qw大哥";
var_dump([
strlen($str),
mb_strlen($str)
]);