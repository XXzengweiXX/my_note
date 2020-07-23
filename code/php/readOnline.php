<?php

function readByline($filename){
    $fp = fopen($filename,'r');
    if(!$fp){
        throw  new \Exception("fail to read {$filename}");
    }
    $contents = [];
    while (!feof($fp)){
        $contents[] = fgets($fp);
    }
    fclose($fp);
    return $contents;
}
$filename = './a.txt';
$contents = readByline($filename);