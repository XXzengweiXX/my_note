# 数据类型

1. string(字符)
2. hash(哈希)
3. list(列表)
4. set(集合)
5. zset(有序集合)

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