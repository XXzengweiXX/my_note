# string

```php
// 反转字符串
strrev("123") //321
// 生成重复字符串
  str_repaet("ab",2);  //"abab"
// 字符串比较
strcmp($str1,$str2);// 返回-1,0,1
```

# array

```php
//value升序,排序后不保持key的关联关系
sort();
//value倒叙,排序后不保持key的关联关系
rsort();
//value升序,排序后保持key的关联关系
asort();
//value倒叙,排序后保持key的关联关系
arsort();
//按key升序,排序后保持key的关联关系
ksort();
//按key倒叙,排序后保持key的关联关系
krsort();
// 对一个或者多个数组排序,可实现多维数组排序array_multisort(array_column($myArr,'age'),SORT_ASC,$myArr);
array_multisort();
// 用户自定义排序
usort($arr,$callble)
  
usort($nums,'cmp');
function cmp($a,$b){
    $sub = $a-$b;
    if($sub==0){
        return 0;
    }
    return $sub>0?1:-1;
}
// usort二维数组排序
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

# 获取二维数组某个key的值,返回数组
array_column($arr,'kay_name')
```

# file

```php
// 创建多级目录 path:路径,mode:权限,如0777,recursive:是否递归,即是否创建多级目录,默认false
mkdir($path,$mode,$recursive)
// 打开文件
// r:只读,指针在文件头
// r+:读写,指针在文件头
// w:只写,指针在文件头,清空文件,文件不存在则创建 
// w+:读写,指针在文件头,清空文件,文件不存在则创建 
// a:只写,指针在文件末尾,文件不存在则创建 
// a+:读写,指针在文件末尾,文件不存在则创建
// x:创建并写入,指针在文件头,若文件已存在则返回fasle 
// x+:创建并读写方式,指针在文件头,若文件已存在则返回fasle
  fopen($filename,$mode)
```

