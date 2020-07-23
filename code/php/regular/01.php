<?php

$str= 'apdsd';

// 字符串中是否包含u或者@
// pre_match:$bool是否匹配成功(0或者1),$res匹配结果
$bool= preg_match("/a|@/",$str,$res);
// 匹配固定格式:010或者020后面-再加上7到8位数字的字符串
$phone="010-14514512";
//var_dump(preg_match("/(010|020)\-\d{7,8}/",$phone));
// 限定字符
// ^:表示以什么开头 /^a/ 以a开头
// $:表示以什么结尾
// 匹配a开头z结尾的字符串
$str = 'ajgjut455z';
//var_dump(preg_match("/^a(.*)z$/",$str));

$url = "http://www.baidu.net";
//var_dump(preg_match("/^https?:\/\/[a-zA-Z0-9]+\.\w+\.(com|cn|net)$/",$url));
$str="hello,ni好压抑,大 ddf";
//var_dump([
//    //字母
//    preg_match("/\p{L}/u",$str),
//    //标点
//    preg_match("/\p{P}/u",$str),
//    //汉字
//    preg_match("/\p{Han}/u",$str),
//
//]);

$aTags = [
    "<a href='01.php'>test</a>",
    "<a id='jiu' href='jiu.php'>jiu</a>",
];
foreach ($aTags as $aTag){
    $bool = preg_match("/<a.*href=(?:\'|\")(?<url>.*)(?:\'|\").*>(?<title>.*)<\/a>/i",$aTag,$res);
    if($bool){
        var_dump([
            $res['url'],
            $res['title']
        ]);
    }
}