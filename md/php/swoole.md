# server

## 创建

> 创建一个异步服务器程序，支持TCP、UDP、TCP6、UDP6、UnixSocket,Stream/Dgram 等种协议

```php
/*
ip:ip
port:监听端口
mode:运行模式,默认SWOOLE_PROCESS
     SWOOLE_BASE(单线程):异步非阻塞,类似于nginx与nodejs,
     如果回调函数中有阻塞操作,server退化为同步模式. 
     此模式下没有master进程,只有多个worker进程,
     worker进程同时承担process模式下reactor线程与worker进程的职责
     此模式下性能更好,代码更简单,但是worker挂了之后,下面的所有连接都会失联,
     无法实现负载均衡
     SWOOLE_PROCESS(进程):多进程模式是最复杂的方式，
     用了大量的进程间通信、进程管理机制。
     适合业务逻辑非常复杂的场景
     特点是连接与数据请求发送是分离的，
     不会因为某些连接数据量大某些连接数据量小导致Worker进程不均衡
     Worker进程发送致命错误时，连接并不会被切断
sockType:协议类型,默认SWOOLE_SOCK_TCP
     SWOOLE_SOCK_TCP:tcp
     SWOOLE_SOCK_UDP:udp
*/
$server = new Swoole\Server($ip,$port,$mode,$sockType)
```

## 运行参数设置

[详情](https://wiki.swoole.com/#/server/setting)

```php
// Server->set 必须在 Server->start 前调用
/*
reactor_num:线程数,建议cpu个数的1-4倍,默认启用于cpu个数相等的数量
worker_num:worker进程数,建议cpu个数的1-4倍
daemonize:守护进程是否开启,1开启,
          启用守护进程后，标准输入和输出会被重定向到 log_file,
          如果未设置log_file，将重定向到 /dev/null，所有打印屏幕的信息都会被丢弃
document_root:静态资源路径设置          
log_file:指定日志文件,log_file不会自动切分文件，所以需要定期清理此文件
log_level:日志级别,0-5之间
          0 => SWOOLE_LOG_DEBUG
          1 => SWOOLE_LOG_TRACE
          2 => SWOOLE_LOG_INFO
          3 => SWOOLE_LOG_NOTICE
          4 => SWOOLE_LOG_WARNING
          5 => SWOOLE_LOG_ERROR
          6 => SWOOLE_LOG_NONE
max_request:worker中最大任务数设置,默认0,base模式下无效
           max_request只能用于同步阻塞、无状态的请求响应式服务器程序, 
           强烈不推荐在异步/协程服务器中使用
task_worker_num:task进程数量设置,配置此参数后会启用task功能,
                此时Server需要注册onTask、onFinish2个事件回调函数。
                如果没有注册，服务器程序将无法启动
task_tmpdir:设置task的数据临时目录，
            在Server中，如果投递的数据超过8180字节，将启用临时文件来保存数据
            底层默认会使用/tmp目录存储task数据
enable_coroutine:内置协程开关设置,默认true   
                 当enable_coroutine设置为true时，底层自动在onRequest回调中创建协程，
                 开发者无需自行使用go函数创建协程
                 当enable_coroutine设置为false时，底层不会自动创建协程，
                 开发者如果要使用协程，必须使用go自行创建协程
max_coroutine:设置当前工作进程最大协程数量。
              超过max_coroutine底层将无法创建新的协程，底层会抛出错误，并直接关闭连接           heartbeat_check_interval:启用心跳检测,此选项表示每隔多久轮循一次，单位为秒   
heartbeat_idle_time:与heartbeat_check_interval配合使用。表示连接最大允许空闲的时间 
                    如设置为600表示一个连接如果600秒内未向服务器发送任何数据，此连接将被强制关闭
*/
$server->set([
  "daemonize"=>1
]);
```

## 注册事件回调

```php
/* 回调函数有4种写法:
                  匿名函数:$server->on('start',function($server){})
                  类静态方法:$server->on('start',"className::functionName")
                           $server->on('start',["className","functionName"])
                  函数:$server->on('start',"functionName")  
                  对象:$server->on('start',[$obj,"item"])
*/  
// 大小写不敏感
// 主进程master启动后调用
$server->on('start',function($server){
  
})
// 当worker或者task进程启动时
$server->on('workerStart',function($server,$workerId){
  
})
// 当worker进程终止时
$server->on('workerStop',function($server,$workerId){
  
})
// 当有新连接时
$server->on('connect',function($server,$fd,$reactorId){
  
})  
// tcp中当worker中接收到数据时
$server->on('receive',function($server,$fd,$reactorId,$data){
  
}) 
// 当udp服务器接收到数据时
$server->on('packet',function($server,$data,$clientInfo){
  //udp发送信息
  $server->sendto($clientInfo['address'],$clientInfo['port'],'udp data')
}) 
// 当客户端断开连接时
$server->on('close',function($server,$fd,$reactorId){
  
}) 
// 当接收到task时
$server->on('task',function($server,$taskId,$workerrId,$data){
  $server->finish($data);
}) 
// 当task完成并调用finsh时
$server->on('finish',function($server,$taskId,$data){
  
})   
```

## 启动服务

```php
$server->start();
```

# client

## 创建

```php
/*
sockType:sock类型,
         支持 SWOOLE_SOCK_TCP、SWOOLE_SOCK_TCP6、SWOOLE_SOCK_UDP、SWOOLE_SOCK_UDP6
isSync:同步阻塞模式，现在只有这一个类型,默认SWOOLE_SOCK_SYNC
key:用于长连接的 Key
*/
$client= new \Swoole\Client($sockType,$isSync,$key);
```

## 参数设置

```php
// set必须在connect前配置
// 目前支持 open_length_check 和 open_eof_check 2 种自动协议处理功能
// 配置好了协议解析后，客户端的 recv() 方法将不接受长度参数，每次必然返回一个完整的数据包
/*
//结束符检测
'open_eof_check' => true,
'package_eof' => "\r\n\r\n",
'package_max_length' => 1024 * 1024 * 2,
//长度检测
'open_length_check' => 1,
'package_length_type' => 'N',
'package_length_offset' => 0, //第N个字节是包长度的值
'package_body_offset' => 4, //第几个字节开始计算长度
'package_max_length' => 2000000, //协议最大长度
*/
$client->set([
    'open_eof_check' => true,
    'package_eof' => "\r\n\r\n",
    'package_max_length' => 1024 * 1024 * 2,
]);
```



## 连接

```php
/*
ip:服务器ip
port:连接端口
timeout:过期事件,浮点数类型,默认0.5
flag:在 UDP 类型时表示是否启用 udp_connect 设定此选项后将绑定 $host 与 $port，
     此 UDP 将会丢弃非指定 host/port 的数据包
     在TCP类型，$flag=1 表示设置为非阻塞 socket，之后此fd会变成异步 IO，connect 会立即返回。
     如果将$flag设置为1,那么在send/recv前必须使用swoole_client_select
     来检测是否完成了连接
     默认0,返回true/false
*/
$client->connect($ip,$port,$timeout,$flag);
```

## 信息发送

```php
$client->send($data);
```

## 信息接收

```php
/*
size:接收数据的缓存区最大长度,默认65535
flag:可设置额外的参数,默认0
*/
$msg = $client->recv($size,$flag);
```

## 关闭

```php
$client->close();
```

