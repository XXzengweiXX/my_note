<?php

class Subscribe{
    protected $redis;
    public function __construct()
    {
        $this->redis = new \Redis();
        $conn = $this->redis->pconnect('127.0.0.1',6379);
        if(!$conn){
            throw new \Exception("fail to connect redis-server");
        }
    }
    // 订阅channel
    public function subscribe($channels){
        $this->redis->subscribe($channels,[$this,'receiveMsg']);
    }
    // 取消订阅
    public function unsubscribe($channels){
        $this->redis->unsubscribe($channels);

    }
    // 退订莫格模式下的channel,为null时退订全部
    public function punsubscribe($pattern=null){
        $this->redis->punsubscribe($pattern);
    }
    // 处理接收到的channel数据
    public function receiveMsg($redis,$channel,$msg){
        echo "from channel_{$channel} get msg:{$msg}\n";
    }
}

$sub = new Subscribe();
$sub->subscribe(['test','test2']);