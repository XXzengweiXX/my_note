<?php 

function scanFilePath($filePath){
  $paths = [];
  $dirs = opendir($filePath);
  while(($file=readdir($dirs))!==false){
    if($file=="."||$file==".."){
      continue;
    }
    $temp = $filePath.'/'.$file;
    if(is_dir($temp)){
      $paths[$temp] = scanFilePath($temp);
    }else{
      $paths[] = $temp;
    }
  }
  closedir($dirs);
  //return $dirs;
  return $paths;
};

$dir="./";
$res = scanFilePath($dir);
var_dump($res);