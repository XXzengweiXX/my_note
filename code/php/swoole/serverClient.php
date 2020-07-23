<?php

class ServerClient{
    protected $client;

    public function __construct()
    {
        $this->client = new \Swoole\Client(SWOOLE_SOCK_TCP);
        if(!$this->client->connect('127.0.0.1',9501)){
            throw new Exception('fail to connect server:'.$this->client->errCode);
        }
    }

    public function send(string $msg){
        $this->client->send($msg);
    }

    public function close(){
        $this->client->close();
    }

    public function receive(){
        return $this->client->recv();
    }
}

$client = new ServerClient();
fwrite(STDOUT,'输入信息:');
$msg = trim(fgets(STDIN));
$client->send("{$msg}");
$recv = $client->receive();
echo "server data:{$recv}\n";
echo "q,q\n";
//var_dump($client->receive());
$client->close();