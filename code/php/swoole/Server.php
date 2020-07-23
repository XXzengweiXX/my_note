<?php

class Server{
    protected $server;

    public function __construct()
    {
        $this->server = new Swoole\Server('127.0.0.1',9501);
        // 服务启动
        $this->server->on('Start',[$this,'onStart']);
        // 当worker或者task进程启动时监听
        $this->server->on('WorkerStart',[$this,'onWorkerStart']);
        //客户端连接监听
        $this->server->on('Connect',[$this,'onConnect']);
        //数据接收监听
        $this->server->on('Receive',[$this,'onReceive']);
        // task任务监听
        $this->server->on('Task',[$this,'onTask']);
        // task完成监听
        $this->server->on('Finish',[$this,'onFinish']);
        // worker/task出错监听
        $this->server->on('WorkerError',[$this,'onWorkerError']);
        // 客户端短开连接
        $this->server->on('Close',[$this,'onClose']);
    }
    // 设置参数
    public function set(array $settings){
        $this->server->set($settings);
    }

    public function onStart(Swoole\Server $server){
        echo "sever#{$server->master_pid} get started...\n";
    }

    public function onConnect(Swoole\Server $server,int $fd){
        echo "client {$fd} get connected\n";
        $server->send($fd,'hello:'.$fd);
        $this->server->task("task test");
    }

    public function onReceive(Swoole\Server $server, int $fd, int $fromId,string $data){
        echo "fd:{$fd} send data:{$data}\n";
        $server->send($fd,'receive data:'.$data);
    }

    public function onWorkerStart(Swoole\Server $server, int $workerId){
        // task进程
        if($server->taskworker){
            echo "taskWorker_{$workerId} start..\n";
        }else{
            //worker进程
            echo "worker_{$workerId} start..\n";
        }

    }

    public function onTask(Swoole\Server $server, int $taskId, int $workerId, $data){
        echo "get task_#{$taskId} data:{$data}\n";
        $server->finish($data);
    }

    public function onFinish(Swoole\Server $server, int $taskId, $data){
        echo "task_#{$taskId} finished:{$data}\n";
    }

    public function onWorkerError(Swoole\Server $server, int $workerId, int $workerPid, int $exitCode, int $signal){
        echo "worker get wrong,workId:{$workerId},code:{$exitCode}\n";
    }

    public function onClose(Swoole\Server $server,int $fd){
        echo "client {$fd} get closed\n";
    }

    // 启动
    public function run(){
        echo "tcp server start now...\n";
        $this->server->start();
    }
}

$server = new Server();
$server->set([
    'reactor_num'   => 2,     // reactor thread num
    'worker_num'    => 4,     // worker process num
    'backlog'       => 128,   // listen backlog
    'max_request'   => 50,
    'task_worker_num'=>20,
    //'dispatch_mode' => 1,
]);
$server->run();