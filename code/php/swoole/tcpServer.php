<?php

class HttpServer{
    protected $server;
    public function __construct()
    {
        $this->server = new Swoole\Http\Server('127.0.0.1',9502);
        $this->server->on('start',array($this,'onStart'));
        $this->server->on('request',array($this,'onRequest'));
    }

    public function onStart(\Swoole\Http\Server $server){
        echo "server start\n";
    }

    public function onRequest(\Swoole\Http\Request $request,\Swoole\Http\Response $response){
        var_dump($request->getData());
        $response->end("hello:{$request->fd}");
    }

    public function run(){
        $this->server->start();
    }
}

$server = new HttpServer();
$server->run();





