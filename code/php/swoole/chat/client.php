<?php

 class Client{
     protected $socket;
     public function __construct()
     {
         $this->socket = stream_socket_client('tcp://127.0.0.1:9501',$errno,$errstr,30);
         if(!$this->socket){
             throw new Exception('fail to connect server:'.$errstr);
         }
     }
     public function onRead(){
         //global $socket;
         $buffer = stream_socket_recvfrom($this->socket,1024);
         if(!$buffer){
             echo "server closed\n";
             swoole_event_del($this->socket);
         }
         echo "\nRECEIVE:{$buffer}\n";
         fwrite(STDOUT,'ENTER MSG:');

     }
     public function onWrite(){
         //global $socket;
         echo "on write\n";
     }

     public function onInput(){
         //global $socket;
         $msg = fgets(STDIN);
         if($msg=='exit'){
             swoole_event_exit();
             exit();
         }
         swoole_event_write($this->socket,$msg);
         fwrite(STDOUT,'ENTER MSG:');
     }

     public function run(){
         swoole_event_add($this->socket,[$this,'onRead'],[$this,'onWrite']);
         swoole_event_add(STDIN,[$this,'onInput']);
         fwrite(STDOUT,'ENTER MSG:');
     }
 }

 $client = new Client();
 $client->run();







