<?php

class Publisher{
    protected $redis;
    public function __construct()
    {
        $this->redis = new \Redis();
        $conn = $this->redis->connect('127.0.0.1',6379);
        if(!$conn){
            throw new \Exception("publisher fail to connect redis-server");
        }
    }

    public function publish($channel,$msg){
        $this->redis->publish($channel,$msg);
    }
}

$pub = new Publisher();
$channels = ['test','test2'];
for($i=1;$i<=20;$i++){
    $channel = $channels[mt_rand(0,1)];
    $pub->publish($channel,'msg_'.$i);
    usleep(500);
}