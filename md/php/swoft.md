# 环境要求

* php版本>=7.1
* 安装有composer
* pcre库
* swoole版本>=4.4.1

# 安装

``` php
composer create-project swoft/swoft Swoft
```

# 运行服务

## http服务

```php
//启动http服务
php ./bin/swoft http:start
//守护模式
php ./bin/swoft http:start -d
//重启
php ./bin/swoft http:restart
//重载    
php ./bin/swoft http:reload
//停止    
php ./bin/swoft http:stop        
```

## websocket服务

```php
php ./bin/swoft ws:start
```

##  rpc服务

```php
php ./bin/swoft rpc:start
```

# 注解

注解（Annotation）又称标注，Java 最早在 2004 年的 JDK 5 中引入的一种注释机制。目前 PHP 官方版本并未提供内置元注解和注解概念，但swoft通过 `ReflectionClass` 反射类解析 PHP 代码注释从而实现了自己的一套注解机制。

## 规范

* 类注解：所有类注释后面
* 属性注解：属性描述之后，其他注释之前
* 方法注解：方法描述之后，其他注释之前

# AOP

## 概念

即**面向切面的程序设计**，是一种设计思想，是OOP的的补充与延申，可以更方便的对业务代码进行解耦，从而提高代码质量和增加代码的可重用性。

## 场景

记录某个接口被调用的详情（参数，返回结果）

## AOP实现

其作用主要是在不侵入原有代码的情况下实现记录功能。

### 声明切片

* 通过注解@Aspect将类定义为切片

* order指定优先级，越小越优先
* PointBean：定义目标类切点
  + include：需被 **指定** 为切点的目标类集合
  + `exclude`：需被 **排除** 为切点的目标类集合
* PointAnnotation：定义 **注解类** 切点，所有使用对应注解的方法均会通过该切面类代理
  + `inlucde`：需被 **织入** 的注解类集合
  + `exclude`：需被 **排除** 的注解类集合
* PointExecution：定义确切的目标类方法。
  + `include`：需被 **织入** 的目标类方法集合，支持正则表达式
  + `exclude`：需被 **排除** 的目标类方法集合，支持正则表达式
* 

```php
<?php declare(strict_types=1);


namespace App\Aspect;

use Swoft\Aop\Annotation\Mapping\After;
use Swoft\Aop\Annotation\Mapping\AfterReturning;
use Swoft\Aop\Annotation\Mapping\AfterThrowing;
use Swoft\Aop\Annotation\Mapping\Around;
use Swoft\Aop\Annotation\Mapping\Aspect;
use Swoft\Aop\Annotation\Mapping\Before;
use Swoft\Aop\Annotation\Mapping\PointAnnotation;
use Swoft\Aop\Annotation\Mapping\PointBean;
use App\Services\OrderService;
use Swoft\Aop\Annotation\Mapping\PointExecution;
use Swoft\Aop\Point\JoinPoint;
use Swoft\Aop\Point\ProceedingJoinPoint;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * @Aspect(order=1)
 *
 * @PointBean(
 *     include={OrderService::class},
 *     exclude={}
 * )
 *
 * @PointAnnotation(
 *     include={RequestMapping::class},
 *     exclude={}
 * )
 *
 * @PointExecution(
 *     include={"OrderService::createOrder"},
 *     exclude={"OrderService::generateOrder"}
 * )
 */
class DemoAspect
{
    /**
     *前置通知，在目标方法之前执行
     *
     * @Before()
     */
    public function beforeAdvice(){

    }
    /**
     * 后置通知，在目标方法之后执行
     *
     * @After()
     */
    public function afterAdvice(){

    }
    /**
     * 环绕通知，等同于前置通知加上后置通知，在目标方法之前及之后执行
     *
     * @Around()
     *
     * @param ProceedingJoinPoint $proceedingJoinPoint
     * @return mixed
     * @throws \Throwable
     */
    public function aroundAdvice(ProceedingJoinPoint $proceedingJoinPoint){
        //前置通知
        $res = $proceedingJoinPoint->proceed();
        //后置通知
        return $res;
    }
    /**
     *返回通知
     *
     * @AfterReturning()
     *
     * @param JoinPoint $joinPoint
     *
     * @return mixed
     */
    public function afterReturnAdvice(JoinPoint $joinPoint){
        $res = $joinPoint->getReturn();

        return $res;
    }
    /**
     * 异常通知,目标方法异常时执行
     *
     * @AfterThrowing()
     *
     * @param \Throwable $throwable
     */
    public function afterThrowingAdvice(\Throwable $throwable){

    }
}
```

# RPC

RPC，是一种远程调用方式（Remote Procedure Call），通过 RPC 我们可以像调用本地方法一样调用别的机器上的方法，用户将无感服务器与服务器之间的通讯。RPC 在微服务当中起到相当大的作用，当然 RPC 不是微服务必须的一种方式，有别的方式也可以实现这种远程调用例如 RESTful API 就可以实现远程调用。如果有用过 SOAP 那么你使用 RPC 将会觉得很类似，都是可以直接调用别的机器上的方法。

## 参数配置

RPC 服务启动有单独启动和集成其它服务 (Http/Websocket) 两种方式，无论那种方式都首先要在 bean.php 配置 RPC。

``` php
//单独启动配置
return [
    'rpcServer'  => [
        'class' => ServiceServer::class,
        'port' => 18308,
    ],
]
//集成启动配置    
return [
    'httpServer' => [
        'class'    => HttpServer::class,
        'port'     => 18306,
        'listener' => [
            'rpc' => bean('rpcServer')
        ],

        // ...
    ],
]
```

## 定义接口

服务提供方定义好接口格式，存放到公共的 `lib` 库里面，服务调用方，加载 `lib` 库，就能使用接口服务，接口定义和普通接口完全一致。

## 接口实现

一个接口，会存在多种不同的实现，通过一个版本号来标识是那个逻辑实现。版本默认是1.0

``` php
<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Rpc\Service;

use App\Rpc\Lib\UserInterface;
use Exception;
use Swoft\Co;
use Swoft\Rpc\Server\Annotation\Mapping\Service;

/**
 * Class UserServiceV2
 *
 * @since 2.0
 *
 * @Service(version="1.2")
 */
class UserServiceV2 implements UserInterface
{
    /**
     * @param int   $id
     * @param mixed $type
     * @param int   $count
     *
     * @return array
     */
    public function getList(int $id, $type, int $count = 10): array
    {
        return [
            'name' => ['list'],
            'v'    => '1.2'
        ];
    }

    /**
     * @return void
     */
    public function returnNull(): void
    {
        return;
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getBigContent(): string
    {
        $content = Co::readFile(__DIR__ . '/big.data');
        return $content;
    }

    /**
     * Exception
     * @throws Exception
     */
    public function exception(): void
    {
        throw new Exception('exception version2');
    }

    /**
     * @param string $content
     *
     * @return int
     */
    public function sendBigContent(string $content): int
    {
        return strlen($content);
    }
}

```

## 客户端配置

```php
return [
    //配置一个user服务
    'user'       => [
        'class'   => ServiceClient::class,
        'host'    => '127.0.0.1',//服务端ip
        'port'    => 18307,//服务端端口
        'setting' => [
            'timeout'         => 0.5,
            'connect_timeout' => 1.0,
            'write_timeout'   => 10.0,
            'read_timeout'    => 0.5,
        ],
        'packet'  => bean('rpcClientPacket')
    ],
    'user.pool'  => [
        'class'  => ServicePool::class,
        'client' => bean('user')
    ],
];
```

## 客户端调用服务

**@Reference 注解**

* `pool` 指定使用那个服务的连接池(使用那个服务)
* `version` 指定服务的版本

``` php
<?php declare(strict_types=1);
namespace App\Http\Controller;

use App\Rpc\Lib\UserInterface;
use Exception;
use Swoft\Co;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Rpc\Client\Annotation\Mapping\Reference;

/**
 * Class RpcController
 *
 * @since 2.0
 *
 * @Controller()
 */
class RpcController
{
    /**
     * @Reference(pool="user.pool")
     *
     * @var UserInterface
     */
    private $userService;

    /**
     * @Reference(pool="user.pool", version="1.2")
     *
     * @var UserInterface
     */
    private $userService2;

    /**
     * @RequestMapping("getList")
     *
     * @return array
     */
    public function getList(): array
    {
        $result  = $this->userService->getList(12, 'type');
        $result2 = $this->userService2->getList(12, 'type');

        return [$result, $result2];
    } 
}

```

# 微服务

## 注册服务

```php
<?php


namespace App\Listener;


use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Consul\Agent;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Http\Server\HttpServer;
use Swoft\Log\Helper\CLog;

/**
 * Class MyRegisterServiceListener
 * @package App\Listener
 *
 * @since 2.0
 *
 * @Listener(event=SwooleEvent::START)
 */
class MyRegisterServiceListener implements EventHandlerInterface
{
    /**
     * @Inject()
     *
     * @var Agent
     */
    private $agent;

    /**
     * @param EventInterface $event
     */
    public function handle(EventInterface $event): void
    {
        // TODO: Implement handle() method.
        /* @var HttpServer $httpServer */
        $httpServer = $event->getTarget();
        $service = [
            'ID' => 'myRegister',//id
            'Name' => 'myRegister',//名称
            'Tags' => [
                'http',
            ],
            'Address' => '127.0.0.1',//地址
            'Port' => $httpServer->getPort(),//端口
            'Meta' => [
                'version' => '1.0',
            ],
            'EnableTagOverride' => false,
            'Weights' => [//权重
                'Passing' => 10,
                'Warning' => 1,
            ]
        ];
        //register
        $this->agent->registerService($service);
        CLog::info('myRegister http register service success by consul!');
    }
}
```

## 注销服务

服务启动注册服务，服务关闭或者退出则需要取消服务注册，此时这里和注册一样监听一个 `SwooleEvent::SHUTDOWN` 事件即可

```php
<?php


namespace App\Listener;


use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Consul\Agent;
use Swoft\Event\Annotation\Mapping\Listener;
use Swoft\Event\EventHandlerInterface;
use Swoft\Event\EventInterface;
use Swoft\Http\Server\HttpServer;
use Swoft\Server\SwooleEvent;

/**
 * Class MyDeregisterServiceListener
 * @package App\Listener
 *
 * @since 2.0
 *
 * @Listener(event=SwooleEvent::SHUTDOWN)
 */
class MyDeregisterServiceListener implements EventHandlerInterface
{
    /**
     * @Inject()
     *
     * @var Agent
     */
    private $agent;

    public function handle(EventInterface $event): void
    {
        // TODO: Implement handle() method.
        /* @var HttpServer $httpServer */
        $httpServer = $event->getTarget();

        $this->agent->deregisterService('myRegister');
    }

}

```

## 服务发现

通过第三方集群 consul 下发可用的服务列表

```php
<?php declare(strict_types=1);

namespace App\Common;

use ReflectionException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Consul\Agent;
use Swoft\Consul\Exception\ClientException;
use Swoft\Consul\Exception\ServerException;
use Swoft\Rpc\Client\Client;
use Swoft\Rpc\Client\Contract\ProviderInterface;

/**
 * Class RpcProvider
 *
 * @since 2.0
 *
 * @Bean()
 */
class RpcProvider implements ProviderInterface
{
    /**
     * @Inject()
     *
     * @var Agent
     */
    private $agent;

    /**
     * @param Client $client
     *
     * @return array
     * @throws ReflectionException
     * @throws ContainerException
     * @throws ClientException
     * @throws ServerException
     * @example
     * [
     *     'host:port',
     *     'host:port',
     *     'host:port',
     * ]
     */
    public function getList(Client $client): array
    {
        // 获取服务列表
        $services = $this->agent->services();

        $services = [

        ];

        return $services;
    }
}

```

## 服务熔断与降级

在分布式环境下，特别是微服务结构的分布式系统中， 一个软件系统调用另外一个远程系统是非常普遍的。这种远程调用的被调用方可能是另外一个进程，或者是跨网路的另外一台主机, 这种远程的调用和进程的内部调用最大的区别是，远程调用可能会失败，或者挂起而没有任何回应，直到超时。更坏的情况是， 如果有多个调用者对同一个挂起的服务进行调用，那么就很有可能的是一个服务的超时等待迅速蔓延到整个分布式系统，引起连锁反应， 从而消耗掉整个分布式系统大量资源。最终可能导致系统瘫痪。断路器（Circuit Breaker）模式就是为了防止在分布式系统中出现这种瀑布似的连锁反应导致的灾难。

配置：

```php
//微服务熔断器
    'breaker'=>[
        'timeout'=>3,//超市时间
        'failThreshold'=>5,//设置连续失败多少次切换阀门
        'sucThreshold'=>5,//设置连续成功多少次切换阀门
        'retryTime'=>5, //熔断器由开启状态到半开状态尝试切换时间
    ],
```

熔断器的使用相当简单且功能强大，使用一个 `@Breaker` 注解即可，Swoft 中的熔断是针对于类里面的方法熔断，只要方法里面没有抛出异常就说明是成功访问的，所以 `@Breaker` 注解可以在任何 bean 对象方法上面使用。

## 服务限流

限流是对稀缺资源访问时，比如秒杀，抢购的商品时，来限制并发和请求的数量，从而有效的进行削峰并使得流量曲线平滑。限流的目的是对并发访问和并发请求进行限速，或者一个时间窗口内请求进行限速从而来保护系统，一旦达到或超过限制速率就可以拒绝服务，或者进行排队等待等。

### 算法

> 计数器

采用计数器实现限流有点简单粗暴，一般我们会限制一秒钟的能够通过的请求数，比如限流 qps 为100，算法的实现思路就是从第一个请求进来开始计时，在接下去的1s内，每来一个请求，就把计数加1，如果累加的数字达到了100，那么后续的请求就会被全部拒绝。等到1s结束后，把计数恢复成0，重新开始计数。

这种实现方式，相信大家都知道有一个弊端：如果我在单位时间1s内的前10ms，已经通过了100个请求，那后面的990ms，只能眼巴巴的把请求拒绝，我们把这种现象称为“突刺现象”。

> 漏桶

为了消除"突刺现象”，可以采用漏桶算法实现限流，漏桶算法这个名字就很形象，算法内部有一个容器，类似生活用到的漏斗，当请求进来时，相当于水倒入漏斗，然后从下端小口慢慢匀速的流出。不管上面流量多大，下面流出的速度始终保持不变。

不管服务调用方多么不稳定，通过漏桶算法进行限流，每10毫秒处理一次请求。因为处理的速度是固定的，请求进来的速度是未知的，可能突然进来很多请求，没来得及处理的请求就先放在桶里，既然是个桶，肯定是有容量上限，如果桶满了，那么新进来的请求就丢弃。

这种算法，在使用过后也存在弊端：无法应对短时间的突发流量。

### 使用

> 控制器限速

```php
<?php declare(strict_types=1);

namespace App\Http\Controller;

use Swoft\Http\Message\Request;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Limiter\Annotation\Mapping\RateLimiter;

/**
 * Class LimiterController
 *
 * @since 2.0
 *
 * @Controller(prefix="limiter")
 */
class LimiterController
{
    /**
     * 根据url进行限流
     * @RequestMapping()
     * @RateLimiter(key="request.getUriPath()")
     *
     * @param Request $request
     *
     * @return array
     */
    public function requestLimiter(Request $request): array
    {
        $uri = $request->getUriPath();
        return ['requestLimiter', $uri];
    }

    /**
     *根据类名+方法名进行限流
     * @RequestMapping()
     * @RateLimiter(rate=20, fallback="limiterFallback")
     *
     * @param Request $request
     *
     * @return array
     */
    public function requestLimiter2(Request $request): array
    {
        $uri = $request->getUriPath();
        return ['requestLimiter2', $uri];
    }

    /**
     *根据url+参数进行限流
     * @RequestMapping()
     * @RateLimiter(key="request.getUriPath()~':'~request.query('id')")
     *
     * @param Request $request
     *
     * @return array
     */
    public function paramLimiter(Request $request): array
    {
        $id = $request->query('id');
        return ['paramLimiter', $id];
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function limiterFallback(Request $request): array
    {
        $uri = $request->getUriPath();
        return ['limiterFallback', $uri];
    }
}
```

