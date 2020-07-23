<?php

// 同步阻塞客户端
class Client{
    protected $client;
    protected $sock;
    public function __construct($sockType=SWOOLE_SOCK_TCP)
    {
        $this->client = new \Swoole\Client($sockType);
    }

    public function set($settings=[]){
        $this->client->set($settings);
    }

    public function connect($host,$port,$timeout=0.5,$flag=0){
        return $this->client->connect($host,$port,$timeout,$flag);
    }

    public function send($data){
        $this->client->send($data);
    }

    public function receive($size=65535){
        return $this->client->recv($size);
    }

    public function close(){
        return $this->client->close();
    }

    public function onInput(){
        $msg = trim(fgets(STDIN));
        if(trim($msg)=='quit'){
            $this->client->close();
            \Swoole\Event::exit();
            exit();
        }
        \Swoole\Event::write($this->sock,$msg);
        fwrite(STDOUT,"请输入信息:");
    }

    public function onReceive(){
        $msg = trim($this->client->recv());
        if(!trim($msg)){
            echo "close client".PHP_EOL;
            \Swoole\Event::del($this->sock);
        }
        echo "receive:{$msg}".PHP_EOL;
        $this->initNotice();
    }

    public function onSend($msg){
        $this->send($msg);
    }

    public function run(){
        $this->sock = $this->client->getSocket();

        \Swoole\Event::add($this->sock,[$this,'onReceive'],[$this,'onSend']);
        \Swoole\Event::add(STDIN,[$this,'onInput']);
        $this->initNotice();
    }
    public function initNotice($msg="请输入信息:"){
        fwrite(STDOUT,$msg);
    }
}

$client = new Client();
if(!$client->connect('127.0.0.1',9500)){
    throw new Exception("fail to connect server");
};
$client->run();

