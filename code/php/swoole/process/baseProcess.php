<?php

class BaseProcess{
    protected $process;

    public function __construct()
    {
        $this->process = new Swoole\Process(
            [$this,'doSomething'],//执行函数
            false,//是否重定向输出
            true//是否使用pipe
        );

        //将此进程转化为守护进程
        //$this->process::daemon(true,true);
    }

    public function doSomething(){
        $num=0;
        //定时写入数据
        swoole_timer_tick(500,function ($timerId)use (&$num){
            $num++;
            $this->process->write("msg_{$num}");

            if($num>=10){
                swoole_timer_clear($timerId);
            }
        });
    }
    //运行进程
    public function run(){
        $this->process->start();
        //设置进程名
        $this->process->name('my_base_process');
        //添加监听事件
        \Swoole\Event::add($this->process->pipe,function (){
            //监听管道读取信息
            $data = $this->process->read();
            echo "receive:{$data}".PHP_EOL;
        });
    }
    //关闭进程
    public function close(){
        echo "process[{$this->process->id}] is closing\n";
        $this->process->close();
    }
}

$process = new BaseProcess();
$process->run();
//进程信号监听
\Swoole\Process::signal(SIGCHLD,function ($signo)use ($process){
    while ($res=\Swoole\Process::wait(false)){
        echo "process pid:{$res['pid']} stop\n";
        $process->close();
    }
});