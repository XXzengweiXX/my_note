<?php

// 异步客户端
class Client{
    protected $client;
    protected $sock;
    protected $host;
    protected $port;
    protected $timeout;
    public function __construct($sockType=SWOOLE_SOCK_TCP)
    {
        //$this->client = new \Swoole\Client($sockType,SWOOLE_SOCK_ASYNC);
        $this->client =new \Swoole\Async\Client($sockType);
    }

    public function run(){
        $this->client->on('connect',[$this,'onConnect']);
        $this->client->on('error',[$this,'onError']);
        $this->client->on('receive',[$this,'onReceive']);
        $this->client->on('close',[$this,'onClose']);

        $this->client->connect($this->host,$this->port,$this->timeout);
    }

    public function setConnect($host,$port,$timeout=0.5){
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
    }

    public function onConnect($client){
        echo "success to connect server".PHP_EOL;
        $this->client->send("hahha");
    }

    public function onError($client){
        echo "client error".PHP_EOL;
    }


    public function onReceive($client,$data){
        echo "get data:{$data}".PHP_EOL;
    }

    public function onClose($client){
        echo "client closed".PHP_EOL;
    }

    public function send($data){
        $this->client->send($data);
    }
}

$client = new Client();
$client->setConnect('127.0.0.1',9501);
$client->run();
//$client->send('hello');

