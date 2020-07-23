# 数据类型

1. string(字符)
2. hash(哈希)
3. list(列表)
4. set(集合)
5. zset(有序集合)

# 操作

```bash
# 查看数据库大小
dbsize
# 切换数据库,默认16个数据库,切换(1--15)
select 3
# 查看所有的key
keys *
keys *a  #获取a结尾的key
keys b*  # 获取b开头的key
# 清空数据库
flushdb  # 清空当前数据库
flushall # 清空所有的数据库
# 查看字段是否存在,存在1,不存在0
exists key
# 将当前数据库的key移动到另一个数据库
move key 2
# 设置过期时间
expire key 10 # 10s
# 查看过期时间
ttl key
# 查看key对应的value类型
type key
```



# string

## SET

关联字符串key与value

`SET key value [EX seconds] [PX milliseconds] [NX|XX]`

> 可选参数
>
> **EX seconds**:过期时间秒,eg:`SET k1 v1 EX 100`
>
> **PX milliseconds**:过期时间毫秒,eg:`SET k2 v2 PX 1000`
>
> **NX**:只在键不存在时， 才对键进行设置操作,eg:`SET k3 v3 NX`

## SETNX

只在键 key不存在的情况下， 将键 key的值设置为 value。若键 `key` 已经存在， 则 `SETNX` 命令不做任何动作.

`SETNT key value`

## SETEX

将键 `key` 的值设置为 `value` ， 并将键 `key` 的生存时间设置为 `seconds` 秒钟;

如果键 `key` 已经存在， 那么 `SETEX` 命令将覆盖已有的值

`SETEX	key seconds value`等同于:

```redis
SET key value
EXPIRE key seconds
```

## PSETEX

用法与作用与SETEX一样,但是设置的时间为毫秒

## GET

获取key对应的字符串值,如果key不存在,返回nil,存在则返回对应的值

GET只能获取字符串类型的值,如果value不是字符串类型,则返回一个错误

`GET key`

## GETSET

修改key对应的value,并返回原来的value,如果原来没有值,返回nil,同样只对字符类型的value有效

`GETSET key`

## STRLEN

获取字符串长度,key不存在,返回0

`STRLEN key`

## APPEND

如果key存在,则追加在value字符串后面,不存在相当于set的作用,返回value的长度

`APPEND key value`

## INCR

自增1,如果key不存在,先设置成0,在自增,如果value不能解析成数字,返回一个错误

返回自增后的值

`INCR key`

## INCRBY

自增,同incr

`INCRYBY key number`

## INCRBYFLOAT

功能同INCREBY,自增量可以是小数

`INCRBYFLOAT key floatnum`

## GETRANGE

截取

`GETRANGE KEY START END`   `GETRANGE NAME 0 5`

## SETRANGE

替换指定位置的字符串

`SETRANG KEY OFFSET VAL`  `SETRANG KEY2 2 DA`

## MSET与MGET

批量设置与获取

`MSET k1 v1 k2 v2 k3 v3`

`MGET k1 k2 k3`

# list

```bash
# 向list添加元素,添加到头部,可以添加多个
lpush key value1 value
# 获取出list里面的元素,起始位置,获取所有的元素起始位置分别设置成0,-1
lrange key 1 2 //按照添加的倒叙取出
lrange key 0 1
#获取并删除第一个元素
lpop key
# 获取第几个值,从0开始
lindex key index # lindex mylist 1
# 获取list长度
llen mylist
# 移除指定元素
lrm mylist num val # lrm mylist 1 one
# 截取元素
ltrim listkey start end # ltrim mylist 1 2此时mylist只剩下原先的第二到第三2个元素了
# 修改指定位置的元素,listkey或者下标不存在会报错
lset listkey 0 new_val
# 将元素插入到指定元素的前面或者后面
# 将new_val插入到val1的前面或者后面,如果存在多个val1,指挥作用与第一个,如果val1不存在,返回-1
linsert listkey before/after val1 new_val 

# 向list添加元素,添加到末尾,可以添加多个
rpush key val1 val2
# 获取并删除最后一个元素
rpop key

# 移除最后一个元素并把它添加到开头
rpoplpush mylist yourlist # 把mylist的最后一个元素删除并添加到yourlist的开头
```

# set

> 无序,不可重复

```bash
# 添加一个或者多个元素
sadd setkey m1 m2
# 查看元素
smembers setkey
# 检查元素是否存在,存在返回1,不存在返回2
sismember setkey val
# 获取set里面的元素个数
scard setkey
# 删除元素
srem setkey val1 val2
# 随机删除并获取改元素
spop setkey
# 随机取出指定个数元素,默认是1
srandmember setkey 2
# 将set1里面的val元素移动到另一个set2里面
smove set1 set2 val
# 差集
sdiff setkey1 setkey2
# 并集
sinter setkey1 setkey2
# 交集
sunio setkey1 setkey2
```

# hash

```bash
# 添加
hset hashkey name zeng
# 如果字段不存在则添加,存在则不操作
hsetnx hashkey name jou
# 添加多个元素
hmset hashkey age 12 sex man
# 获取
hget hashkey name
# 获取多个
hmget hashkey name age 
# 获取所有
hgetall hashkey
# 删除一个或者多个字段
hdel hashkey name age
# 获取元素长度
hlen hashkey
# 判断某个字段是否存在(1/0)
hexists hashkey name
# 分别获取hash的字段与值
hkeys hashkey
kvals hashkey
# 字段自增自减
hincre hashkey age 1

```

# zset

```bash
# 添加一个或者多个值,score表示排序
zadd zsetkey score val  # zadd myzset 1 one 2 two
# 查看全部,从小到大
zrange zsetkey 0 -1
# 从大到小排序
zrevrange zsetkey 0 -1
# 获取指定区间的元素个数
zcount zsetkey 1 3
```

# geo

```bash
# 添加一个或者多个给数据,如果元素已存在,则会更新数据
# long 经度 -180---180
# lat 维度 -85.05---85.05
geoadd cities long lat nanme  long2 lat2 name2
# 获取一个或者多个位置
geopos cities beijing shanghai 
# 获取两个位置的距离,默认单位m,可以设置位km
geodist cities beijing shanghai
geodist cities beijing shanghai km
# 获取给定位置某个范围内的所有元素,可以
# 指定单位(m,km等),
# withdis(返回距离信息)
georadius cities 110 20 500 km
georadius cities 110 20 500 km withdist
#指定返回数据个数
georadius cities 110 20 500 km count 2
# 按距离排序 asc  desc
georadius cities 110 20 500 km asc
# 获取某个元素给定半径内的其他元素(返回数据包括自身)
georadiusbymember cities chengdu 1500 km
# 查看所有元素
zrange cities 0 -1
# 删除一个或者多个元素
zrem cities beijing shanghai
```

# hyperloglog

> 主要用于基数统计,有一定的容错率

```bash
# 添加或者更新一个或者多个基数
pfadd logkey a b c d
# 基数个数统计
pfcount logkey
# 多个集合合并
pfmerge logkey3 logkey1 logkey2 #将logkey1与logkey2里面的基数合并到logkey3中
```

# bigmap

> 位图,值只有0与1,可用于统计,如打卡签到等

```bash
# 给offset位置设置值
setbit sign offset val
# 获取值
getbit sign offset
# 统计值为1的数量,可以加上start与end这种范围,不加则表示所有
bitcount sign start end
bitcount sign
# 获取第一个给定值的位置 可以设置start与end的范围
bitpos sign 0
```

# 事务

> redis里面的事务没有隔离级别
>
> redis里面的单条命令是原子性的,但是事务不保证原子性

```bash
multi # 开启事务
set k1 v2 # 操作命令,命令入队
set k2 v2
del k3 v3
exec # 执行命令,完成事务
# 放弃事务
discard 
# 监听一个或者多个key,如果这些key在事务执行之前被修改,事务则会被打断
watch k1 k2
# 取消监听
unwatch
```

# 配置文件

```sh
# 大小单位,且大小写不敏感
# 1k => 1000 bytes
# 1kb => 1024 bytes
# 1m => 1000000 bytes
# 1mb => 1024*1024 bytes
# 1g => 1000000000 bytes
# 1gb => 1024*1024*1024 bytes

# 可以使用include导入其他配置文件
# include /path/to/local.conf
# include /path/to/other.conf

# 网络配置
bind 127.0.0.1 192.168.1.6 # 绑定ip,可以绑定多个,任意ip:0.0.0.0
protected-mode yes # 保护模式
port 6379 #端口设置

# 通用配置
daemonize yes # 是否开启守护模式,默认no
pidfile /var/run/redis/redis-server.pid # 如果开启守护模式,需要指定pid文件
loglevel notice # 日志界别设置:debug,verbose,notice,warning
logfile /var/log/redis/redis-server.log # 指定记录日志文件
databases 16 # 默认数据库数量

# 快照设置
# 持久化规则
save 900 1 #900秒至少一个key发生修改,就执行持久化操作
save 300 10 #300秒至少10个key发生修改,就执行持久化操作
save 60 10000 #600秒至少10000个key发生修改,就执行持久化操作
stop-writes-on-bgsave-error yes # 持久化出错,是否还需要继续运行工作
rdbcompression yes #是否压缩rdb文件,开启会额外消耗cpu性能
rdbchecksum yes # rdb文件保存时是否进行错误校验
dbfilename dump.rdb # rdb文件名
dir /var/lib/redis # rdb文件保存目录

# 主从复制,开启主从复制之后,只有主机能写,从机只能读
# slave首次连接到master,会全量复制,之后master有所修改,slave会增量复制信息
slaveof <masterip> <masterport> # 在从机中配置主机信息,实现主从同步
masterauth <master-password>  # 主机的密码

# 安全配置
requirepass foobared # 密码设置,默认注释了,没有密码
# 命令行设置验证密码
config set requirepass 123456
auth 123456

# 客户端配置
maxclients 10000 # 最大连接数,默认10000

# 内存设置
maxmemory <bytes> # 最大使用的内存
maxmemory-policy noeviction # 内存满了之后的采用的策略,默认noeviction
#策略有:
  # volatile-lru:从设置了过期时间的key中删除最近最少使用的key 
  # volatile-ttl:从设置了过期时间的key中删除即将过期的key
  # volatile-random:从设置了过期时间的key中随机删除key
  # allkeys-lru:从所有的key中删除最近最少使用的key 
  # allkeys-random:从所有的key中随机删除key
  # noeviction:永不过期，返回错误
  
# APPEND ONLY MODE,aof配置
appendonly no # 是否开启aof,默认no,大部分情况下rdb满足已需求
appendfilename "appendonly.aof" #aof文件名
appendfsync everysec #同步策略 always:每次修改都同步,everysec:每秒同步一次,no:不同步
no-appendfsync-on-rewrite no # 是否开启aof文件重写策略,默认no,设置为yes后,当aof文件超过设置的大小时,会把redis里面的现有的值读取一遍,用一条命令去记录键值对,以代替之前的多条命令
auto-aof-rewrite-percentage 100 # 设置aof文件增长比例
auto-aof-rewrite-min-size 64mb # 设置aof触发重写的文件大小
```

# 发布/订阅

```bash
# 订阅一个或者多个channel
subscribe chan1 chan2
# 发布消息
publish chan1 msg1
```

# 主从复制

```bash
# 查看信息
info replication
# 配置好redis之后,主机不操作,在从机上执行:slaveof 127.0.0.1 6379
slaveof master_host master_port
```

# 哨兵模式

> 监视多个redis,如果master挂了可以选举出新的master,保证功能的非常使用

```bash
# 设置哨兵配置文件(sentinel.conf)
sentinel monitor redis_master 127.0.0.1 6379 1
```

