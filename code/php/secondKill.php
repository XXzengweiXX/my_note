<?php

include_once "./redisClient.php";

class SecondKill{
    private $redis=null;
    public function __construct()
    {
        $this->redis = RedisClient::getInstance();
    }

    public function addGoods(){
        //$redis = RedisClient::getInstance();
//$data = $redis->keys('*');
//print_r($data);
//phpinfo();
        $ids = range(1,30);
        $keyName = 'sec_kill_goods';
        $this->redis->del($keyName);
        $this->redis->rPush($keyName,...$ids);
        print_r($this->redis->lRange($keyName,0,-1));
    }

    public function buyGoods(){
        $uid = uniqid('',true);
        $keyName = 'sec_kill_goods';
        $successKey = 'sec_kill_order';
        $failNumKey = 'sec_kill_fail';
        $id = $this->redis->lPop($keyName);
        if($id){
            $this->redis->hSet($successKey,$id,$uid);
        }else{
            $this->redis->incr($failNumKey);
        }
    }
}
$secKill = new SecondKill();
for ($i=0;$i<3000;$i++){
    $secKill->buyGoods();
}


