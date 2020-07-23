<?php

class Person{

    public function name(){
        return 'name';
    }

    public function sex(){
        return 'sex';
    }

    // 调用不存在的方法时自动调用
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        echo "method:{$name},args:".print_r($arguments,true);

    }

    public static function __callStatic($name,$arguments){
        echo "method:{$name},args:".print_r($arguments,true);
        //return self::sex();
        call_user_func_array([__CLASS__,'name'],$arguments);
    }
}

$person = new Person();
var_dump( $person->name());
$person->age();
var_dump($person::foo());