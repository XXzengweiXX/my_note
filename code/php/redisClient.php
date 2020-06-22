<?php

class RedisClient{
  private static $instance;
  private $config = [
    'host'=>'127.0.0.1',
    'port'=>6379,
    'timeout'=>300,
    'password'=>'',
    'dbId'=>0,//数据库id号
    'prefix'=>'',//key前缀
  ];
  private function __construct(){
    
    self::$instance=new \Redis();
    $res = self::$instance->connect($this->config['host'],$this->config['port'],$this->config['timeout']);
    if($res===false){
      self::$instance=null;
        throw new \Exception("fail to connect to ".$this->config['host'].":".$this->config['port']);
    }
    if(isset($this->config['password'])&&$this->config['password']!==''){
      self::$instance->auth($this->config['password']);
    }
    $pinRes =self::$instance->ping();

    if(!in_array($pinRes,['PONG','+PONG'])){
      self::$instance=null;
      throw new \Exception("can not ping ".$this->config['host'].":".$this->config['port']);
    }
  }

  public static function getInstance(){
    if(self::$instance==null){
      new self;
    }
    return self::$instance;
  }
}