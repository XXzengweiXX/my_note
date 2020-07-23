<?php

class HttpServer{
    // httpServer不可使用onReceive,但是task,worker,timer还可以使用
    protected $server;
    public function __construct()
    {
        $this->server = new \Swoole\Http\Server('127.0.0.1',9501);
        // 服务启动
        $this->server->on('Start',[$this,'onStart']);
        // 当worker或者task进程启动时监听
        $this->server->on('WorkerStart',[$this,'onWorkerStart']);
        // request监听
        $this->server->on('Request',[$this,'onRequest']);
    }

    public function set(array $settings){
        $this->server->set($settings);
    }
    // 服务启动
    public function onStart(Swoole\Server $server){
        echo "http sever#{$server->master_pid} get started...\n";
    }
    // 当worker或者task进程启动时监听
    public function onWorkerStart(Swoole\Http\Server $server, int $workerId){
        // 自动注册类
        spl_autoload_register(function ($class){
            $file = str_replace('\\',DIRECTORY_SEPARATOR,__DIR__."\\".$class).'.php';
            $file = str_replace('//',DIRECTORY_SEPARATOR,$file);
            echo "register file:{$file}\n";
            if(is_file($file)){
                require $file;
            }else{
                echo "file: {$file} is not exited\n";
            }
        });
        // task进程
        if($server->taskworker){
            echo "taskWorker_{$workerId} start..\n";
        }else{
            //worker进程
            echo "worker_{$workerId} start..\n";
        }
    }
    // request请求
    public function onRequest(\Swoole\Http\Request $request,\Swoole\Http\Response $response){
        $pathInfo = $request->server['path_info'];
        $pathInfo = ltrim($pathInfo,"/");
        echo "path_info:{$pathInfo}\n";
        $pathArr = explode('/',$pathInfo);
        //var_dump($pathArr);
        foreach ($pathArr as $key=>$item){
            $pathArr[$key] = $this->toCamelCase($item);
        }
        $pathNum = count($pathArr);
        $controller = 'Index';
        $action = 'index';
        $class = '';
        //echo "path_num:{$pathNum}\n";
        if($pathNum==0){
            $class = "\\App\\Controllers\\{$controller}";
        }elseif ($pathNum==1){
            $controller = $pathArr[0];
            $class = "\\App\\Controllers\\{$controller}";
        }else{
            $action = $pathArr[$pathNum-1];
            $controller = $pathArr[$pathNum-2];
            unset($pathArr[$pathNum-1]);
            unset($pathArr[$pathNum-2]);
            $class = "\\App\\Controllers\\".implode('\\',$pathArr).'\\'."{$controller}";
        }
        $action = lcfirst($action);
        if(!class_exists($class)){
            $res = "class:{$class} dose not exist\n";
            $response->end($res);
            return;
        }
        if(method_exists($class,$action)){
            $res = (new $class())->$action($request,$response);

        }else{
            $res = "class:{$class} dose not have method:{$action}";
        }
        $response->end($res);
        return;

        // get post cookie files只有参数存在时才有数据,在使用前最好isset判断一下
        var_dump([
            'get'=>$request->get,
            'post'=>$request->post,//限定为content-type为application/x-www-form-urlencoded格式
            'raw'=>$request->rawContent(),//可以接收json或者xml的数据
            'header'=>$request->header,
            'data'=>$request->getData(),
        ]);
        // 设置响应的header
        $response->header('token',md5(mt_rand(1,100)));
        // cookie设置
        $response->cookie('test_cookie',9527,35);
        //设置状态码
        $response->status(200);
        //重定向
        //$response->redirect('http://www.baidu.com',302);
        //分段发送内容,长度不超过2M
        $response->write('<h1>hello</h1>>');
        $response->write('<h2>kitty</h2>>');
        //发送文件,可以实现断点下载
        //$response->sendfile('a.txt');
        //结束响应发送
        $response->end('<p>just end</p>');

    }
    // 字符转驼峰
    private function toCamelCase($string,$tag='_'){
        $arr = explode($tag,$string);
        $newArr=array_map(function ($item){
            return ucfirst($item);
        },$arr);
        return implode('',$newArr);
    }

    public function run(){
        $this->server->start();
    }
}

$server = new HttpServer();
$server->set([
    'reactor_num'   => 2,     // reactor thread num
    'worker_num'    => 4,     // worker process num
    'backlog'       => 128,   // listen backlog
    'max_request'   => 50,
    //'task_worker_num'=>20,
]);
$server->run();