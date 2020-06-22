<?php 
//算出两个文件的相对路径，如$a = '/a/b/c/d/e.php', $b = '/a/b/12/34/c.php', 计算出$a的相对$b路径
function getRelativePath($pathA,$pathB){
  $aPathArr = explode('/',$pathA);
  $bPathArr = explode('/',$pathB);
  $num = 0;
  foreach($aPathArr as $key=>$val){
    if(isset($bPathArr[$key])&&$val==$bPathArr[$key]){
      $num++;
    }else{
      break;
    }
  }
  $relativePath='';
  $aLeftPath = array_splice($aPathArr,count($aPathArr)-$num);
  $relativePath .=str_repeat('../',count($bPathArr)-$num-1);
  $relativePath .= implode('/',$aLeftPath);
  return $relativePath;
}
$aUrl = '/a/b/c/d/g/e.php';
$bUrl = '/a/b/12/34/c.php';
$res = getRelativePath($aUrl,$bUrl);
var_dump($res);