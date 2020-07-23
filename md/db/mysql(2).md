| 名称      | 范围                    | 说明            |
| --------- | ----------------------- | --------------- |
| char      | 固定长度字符串          | 0~255个字符     |
| varchar   | 变长字符串              | 最大65532个字节 |
| tinytext  | 非常小得字符串          | 最大2^8个字节   |
| text      | 小字符串                | 最大2^16个字节  |
| mediutext |                         | 最大2^24个字节  |
| longtext  |                         | 最大2^32个字节  |
| enum      | 枚举,只能有一个枚举的值 | 最多65535个成员 |
| set       | 多选的枚举              | 最多64个成员    |

# 数据库

```mysql
# 创建数据库
create database `test`;
drop database `test`;
```

# 引擎

## Innodb

事务型数据库首选引擎,支持事务,行锁,外键

## MyISAM

有较高的查询与插入速度,不支持事务

# 数据表

## 创建

```mysql
create table `test`(
    `id` int(11) primary key not null auto_increment,
    `name` varchar(20) not null default '',
    `age` int(3) not null default 0 comment '年龄',
    `email` varchar(20) unique
) ENGINE=InnoDB COMMENT='表注释';
```

## 删除

```mysql
drop table if exists `test`,`test2`;
```

## 表操作

```mysql
# 查看表结构
show create `test` \G;
# 修改表名
alter table `test` rename `new_test`;
# 修改字段类型
alter table `new_test` modify `name` varchar(30);
# 修改字段名,如果最后不添加字段类型,类型保持不变
alter table `new_test` change `name` `new_name`;
alter table `new_test` change `new_name` `old_name` varchar(23);
# 添加字段
alter table `new_test` add `phone` varchar(11) not null default '' after `name`;
# 删除字段
alter table `new_test` drop `age`;
# 修改引擎
alter table `new_test` engine=MyISAM;
```



## 数据操作

```mysql
select * from `test`;
select `id`,`name` from `test`;
insert into `test` (`name`,`email`,`age`) values ("tom","tom@163.com",12);
update `test` set `name`="jim" where `id`=1;
delete from `test` where `id`=1;
```

# 数据类型

## 整数

| 名称      | 空间 | 无符号   | 有符号       |
| --------- | ---- | -------- | ------------ |
| tinyint   | 1    | 0~255    | -128~127     |
| smallint  | 2    | 0~2^16-1 | -2^15~2^15-1 |
| mediumint | 3    | 0~2^24-1 | -2^23-2^23-1 |
| int       | 4    | 0~2^32-1 | -2^31-2^31-1 |
| bigint    | 8    | 0~2^64-1 | -2^63-2^63-1 |

>  int(4)中4表示有4表示宽度,即位数,宽度与范围没有关系,只要取值不超过整数的范围,则数据可以插入,不会报错.

## 日期与时间

| 名称      | 空间 | 格式                | 范围                                    | 其他                                                         |
| --------- | ---- | ------------------- | --------------------------------------- | ------------------------------------------------------------ |
| year      | 1    | YYYY                | 1901~2155                               | 4位时范围为1901~2155,2位时,0~69表示2000~2069,70~99表示1970~1999 |
| time      | 3    | HH:MM:SS​            | -838:59:59~838:59:59                    | 小时部分大于24是用来表示某个事件过去的时间或者两个事件之间的间隔 |
| date      | 3    | YYYY-MM-DD          | 1000-01-01~9999-12-31                   | 当年份是2位数时,取值同year,可以使用current_date()或者now()来插入当前时间 |
| datetime  | 8    | YYYY-MM-DD HH:MM:SS | 1000-01-01 00:00:00~9999-12-31 23:59:59 | 当年份是2位数时,取值同year                                   |
| timestamp | 4    | YYYY-MM-DD HH:MM:SS | 1970-01-01 00:00:01~2038-01-19 03:14:07 |                                                              |

## 文本字符串



# 函数

## 使用方法

mysql中可以使用一些函数,如三角函数,对数函数,随机数函数,函数错误时返回null

```my
//常用函数
// 绝对值 abs(num)
// π pi()
// 平方根 sqrt(num)
// 取余 mod(x,y)
// 向上取整 ceil(x)
// 向下取整 floor(x)
// 随机数 rand(x):随机数范围0到1.0之间,整数x可不传入,传输则作为整数,可以产生重复的随机数
// 四舍五入 round(x)  
//         round(x,y):保留指定位数(y)的小数,y为负数是,小数点左边y位数置为0
// 数字截取 truncate(x,y):保留y位小数
// 获取参数符号 sign(x):x为正,0,负时分别返回1,0,-1
// 幂函数 pow(x,y),power(x,y):x的y次方
//       exp(x):e的x次方
// 对数 log(x):x的对数;
//     log10(x):x相对于基数为e的对数
// 字符长度 char_length(str):一个汉字与一个字母数字长度都为1
//         length(str):一个汉字长度为3,一个字母数字长度为1
// 合并字符串 concat(str1,str2...):如果其中一个参数为null,返回结果为null,
//                               如果其中一个参数为二进制字符串,则返回二进制字符串
//          concat_ws(sep,str1,str2...):使用分隔符sqp拼接字符串
// 大小写转换 lower(str),lcase(str):转换为小写
			 upper(str),ucace(str):转换为大写
// 字符串逆序 reverse(str)
// 获取当前时间 curdate()/current_date():返回格式YYYY-MM-DD
			  curdate()+0:返回格式:YYYYMMDD
			  curtime()/current_time():返回格式HH:MM:SS
			  curtime()+0:返回格式:HHMMSS
			  CURRENT_TIMESTAMP()/LOCALTIME()/NOW()/SYSDATE():返回格式YYYY-MM-DD HH:MM:SS
// 时间戳 unix_timestamp(date):不传date参数返回当前时间戳,date可以是date,datetime,timestamp字符							  串,或者YYMMDD/YYYYMMDD格式的数字
// 月份 month(date):返回1-12
	   monthname(date):返回对应月份的英文
// 星期 dayname(date):返回对应周几的英文
	   dayofweek(date):返回1-7,从周日开始
	   weekday(date):返回0-6,从周一开始
	   week(date)/weekofyear(date):一年中的第几周
// 天 dayofyear(date):一年中的第几天
	  dayofmonth:一月中的第几天
// 计算时间与日期 date_add(date,INTERVAL expr type):date为date/datetime的值,
												  expr为一个表达式,指定增加或减少的时间,
												  type指示了expr被解释的方式
				date_add("2019-02-29 11:49:00",INTERVAL 1 MINUTES)
// 条件判断 if(expr,v1,v2):expr为true时,返回v1,否则返回v2
		   ifnull(v1,v2):如果v1不为null,返回v1,否则返回v2
		   case expir when v1 then r2 when v2 then r2 else v3 end:如果expir等于vn,就返回对应的then后的值,都不满足,返回else对应的值
		   SELECT CASE 2 WHEN 2 THEN "two" WHEN 3 THEN "three" ELSE "default" END;
		   select case 1<2 then "small" else "big" end;
// 查看版本  version()
// ip转数字 inet_aton(ip)
// 数字转ip inet_ntoa(int)
// 重复执行指定操作 benchmark(count,expire):count次数,expire操作
				  SELECT BENCHMARK(300000,PASSWORD("dididada"));
select abs(3.3),abs(-3.3),abs(33)
```

# 查询

## select

查询字段,*通配符,查询所有,使用as重命名字段

```mysql
select `f_id`,`f_name` from `a_fruits`;
select * from `a_fruits`;
select `f_id`,`f_name` as friut_name from `a_fruits`;
```

## like

模糊查询,通配符有`%`与`_`,`%`匹配任意长度,`_`匹配一个字符的长度

```mysql
select * from `a_fruits` where `f_name` like "%ple";
select * from `a_fruits` where `f_name` like "ap%";
select * from `a_fruits` where `f_name` like "a%e";
select * from `a_fruits` where `f_name` like "appl_";
select * from `a_fruits` where `f_name` like "a__le";
```

## null

```mysql
select * from `a_friuts` where `f_name` is null;
SELECT * FROM `a_friuts` WHERE `f_name` IS NOT NULL;
```

## distinct

去除重复

```mysql
SELECT DISTINCT `s_id` FROM `a_friuts`;
```

## group by

分组,基本语法一般为``[group by 字段] [ having 条件表达式 ]``

group by通常与集合函数配合使用,如count(),sum(),min(),max(),avg()等等,

也可以使用group_concat()将分组中的字段显示出来,

having用于过滤分组,满足条件的分组才能显示出来.

having与where的区别:having是在分组之后进行过滤来选择分组,where是在分组之前对数据进行筛选,同时where排除掉的数据不参与分组

```mysql
SELECT `s_id`,GROUP_CONCAT(`f_name`) AS `name`,COUNT(*) AS total FROM `a_friuts` GROUP BY `s_id` HAVING `total`>1;
```

使用with rollup关键字之后会在记录最后添加一条数据,用于统计所有数据综合,此时会把having过滤掉的数据也统计进去,rollup与order by不能同时使用,是相互排斥的

```mysql
SELECT `s_id`,GROUP_CONCAT(`f_name`) AS `name`,COUNT(*) AS total FROM `a_friuts` GROUP BY `s_id` WITH ROLLUP;
```

## limit

limit [offset, ] length:两个参数时,第一个参数表示偏移量,第二个参事表示长度,只有一个参数时表示长度

```mysql
// 从第二条数据开始取2条数据
SELECT * FROM `a_friuts` LIMIT 1,2;
// 取前2条数据
SELECT * FROM `a_friuts` LIMIT 2;
```

## 连接查询

关系型数据库的主要特点,主要包括内连接,外连接等,通过连接运算符实现多表查询.

### 内连接(inner join)

使用比较运算符进行表间某些数据的比较操作,列出这些表中与连接条件相匹配的数据,形成新的记录.两种语法是相同的效果,不过一般采用第二种语法

内连接返回的结果仅仅是符合查询条件与连接条件的数据

```mysql
SELECT `a_friuts`.`f_id`,`a_friuts`.`s_id`,`a_friuts`.`f_name`,`a_friuts`.`f_price`,`a_suppliers`.`s_name`,`a_suppliers`.`s_city`
FROM `a_friuts`,`a_suppliers`
WHERE `a_friuts`.`s_id` = `a_suppliers`.`s_id`;
```

```mysql
SELECT `a_friuts`.`f_id`,`a_friuts`.`s_id`,`a_friuts`.`f_name`,`a_friuts`.`f_price`,`a_suppliers`.`s_name`,`a_suppliers`.`s_city`
FROM `a_friuts`
INNER JOIN `a_suppliers`
ON `a_friuts`.`s_id` = `a_suppliers`.`s_id`;
```

### 外连接

分为左连接与右连接

左连接(left join):返回包括左表的所有记录与右表中连接字段相等的数据,若右表没有数据,则字段为null

右连接(right join):返回包括由表的所有记录与左表中连接字段相等的数据,若左表没有数据,则字段为null

```mysql
SELECT `a_friuts`.`f_id`,`a_friuts`.`s_id`,`a_friuts`.`f_name`,`a_friuts`.`f_price`,`a_suppliers`.`s_name`,`a_suppliers`.`s_city`
FROM `a_friuts`
LEFT JOIN `a_suppliers`
ON `a_friuts`.`s_id` = `a_suppliers`.`s_id`;
```

```mysql
SELECT `a_friuts`.`f_id`,`a_friuts`.`s_id`,`a_friuts`.`f_name`,`a_friuts`.`f_price`,`a_suppliers`.`s_name`,`a_suppliers`.`s_city`
FROM `a_friuts`
right JOIN `a_suppliers`
ON `a_friuts`.`s_id` = `a_suppliers`.`s_id`;
```

### 子查询

指一个查询语句嵌套在另一个查询语句内部的查询,可以基于一个或者多个表,常用操作符有any(some),all,in,exists,子查询可以添加到select,update,delete语句中,也可以进行多层嵌套.

any与some同义词,表示满足任一条件

```mysql
SELECT `num` FROM `a_num1` WHERE `num` > ANY (SELECT `num` FROM `a_num2`);
SELECT `num` FROM `a_num1` WHERE `num` IN (SELECT `num` FROM `a_num2`);
```

all:同时满足所有子查询条件

```mysql
SELECT `num` FROM `a_num1` WHERE `num`  > ALL (SELECT `num` FROM `a_num2`);
```

exists:对子查询进行运算以判断是否返回行,若子查询字少返回一行,结果为true,则外部查询语句执行,如果子查询没有数据返回,结果为false,外部语句不执行

```mysql
SELECT * FROM `a_friuts` WHERE EXISTS (SELECT `s_name` FROM `a_suppliers` WHERE `s_id`=104);
```

### 联合查询

利用UNION关键字,将多个查询结果组合成一个结果集合,合并时所有的结果的字段数量与类型必须相同.

UNION ALL不对结果做处理,UNION会对结果集合做去重处理.

```mysql
SELECT `f_id`,`s_id`,`f_name`,`f_price` FROM `a_friuts` WHERE `s_id`<105 
UNION 
SELECT `f_id`,`s_id`,`f_name`,`f_price` FROM `a_friuts` WHERE `f_price`<8;
```

# 数据插入

基本语法:`insert into table_name (col1,col2,...) values (val1,val2,...),(val11,val22,...);  `

将查询到的数据直接插入到数据表中

```mysql
INSERT INTO `a_person` (`name`,`age`,`info`) SELECT `name`,`age`,`info` FROM `a_person` WHERE `id`in (1,2,5);
```

# 更新

基本语法:`update table_name set col1=val,col2=val2,... where <condition>`

# 删除

基本语法: `delete from table_name where <condition>`

# 索引

## 简介

是一个单独的,储存在磁盘中的数据结构,包含着对数据表里又有记录的引用指针,可以用其快速的找出一列或者多列有一特定值的行.如果没有索引,查询时需要遍历整张表,如果有索引,可以通过索引直接找到对应数据的位置,从而提高查询速度.

## 分类

* 唯一索引:对应列的值必须唯一,允许有空值;如果是组合索引,列值的组合必须唯一.主键是一种特殊的唯一索引,不能为空值.

* 普通索引:基本的索引类型,可以是重复值/空值

* 单列索引:一个索引只包含单个列,一张表中可以有多个单列索引
* 组合索引:索引包含多个字段,查询时只有条件中包含了左边的字段,索引才会生效
* 全文索引:在对应的列上支持对值的全文查找,可以在char,varchar,text类型的列上创建,仅MyISAM引擎支持
* 空间索引:在空间数据类型的字段上建立的索引,空间数据类型有GEOMETRY,POINT,LINESTRING,POLYGON,仅MyISAM引擎支持

## 创建

创建表时创建索引

```mysql
# 唯一索引
create table t1(
`id` int(11),
`name` varchar(20),
 UNIQUE INDEX Uid(id)   
);
# 普通索引
create table t1(
`id` int(11),
`name` varchar(20),
 INDEX Id_index(id)   
);
# 组合索引
create table t1(
`id` int(11),
 `age` int(11),   
`name` varchar(20),
 INDEX Mul_index(age,name)   
);
# 全文索引
create table t1(
`id` int(11),
`name` varchar(20),
 `info` text,
 FULLTEXT INDEX f_index(info)   
);
```

在已存在的表上添加索引

```mysql
alter table `table_name` add INDXE name_index(name);

create index name_index on `table_name`(`column_name`);
```

删除索引

```mysql
alter table `table_name` drop INDEX index_name;

drop INDEX index_name on `table_name`;
```

# 储存过程与函数

创建储存过程与函数的语句分别是`CREATE PROCEDURE`与`CREATE FUNCTIION,`可以使用CALL来调用储存过程,函数可以在语句外调用.储存过程可以调用其他储存过程.

## 创建储存过程

```mysql
CREATE PROCEDURE pc_name([params])
```

参数格式`[IN|OUT|INOUT] param1 type`

IN:输入参数,OUT:输出参数,INOUT:既可以输入也可以输出,type为字段的类型

```mysql
DELIMITER //
CREATE PROCEDURE AvgFriutsPrice()
BEGIN
SELECT AVG(`f_price`) FROM `a_friuts`;
END //
DELIMITER
```

### 创建函数

```mysql
DELIMITER //
CREATE FUNCTION GetCity ()
RETURNS VARCHAR(50)
RETURN (SELECT `s_name` FROM `a_suppliers` WHERE `z_zip`=60030);
//
DELIMITER;
```

### 变量

变量的声明,使用declare,需要变量的类型,可以设置变量的默认值

```mysql
DECLARE varName INT DEFAULT 1;
```

变量赋值使用set

```mysql
SET varName = 2;
```

### 光标的声明与使用

当查询结果返回数据量比较大的时候,可以在存储过程与函数中使用光标来逐条读取数据.

**光标只能在存储过程与函数中使用**

声明:`declare cousorName cursor for selectStatment`

```mysql
declare cursorFruit cursor for select `f_name`,`f_price` from `a_friuts`;
```

打开光标

```mysql
open cursorFruit;
```

使用光标

```mysql
fetch cursorFruit into fname,fprice;
```

关闭光标

```mysql
close cursorFruit;
```

### 调用储存过程

```mysql
call pc_name(param1,parm2,...);
```

### 查看存储过程定义

```mysql
show create procedure pc_name;
```

### 删除存储过程

```mysql
drop procedure if exists pc_name;
```



## 流程控制

### if

```mysql
IF val IS NULL
THEN SELECT "val is null";
ELSE SELECT "val is not null";
END IF;
```

### case

```mysql
CASE val
  WHEN 1 THEN SELECT "val is 1";
  WHEN 2 THEN SELECT "val is 2";
  ELSE SELECT "default val";
END CASE;
```

### loop

循环

```mysql
# 格式
[loop_label:] loop
  statement_list;
end loop [loop_lable]  

# 实例
# leave可以跳出循环
declare num int default 0;
addLoop:loop
set num = num+1;
  if num>10 then leave addLoop;
  end if;
end loop addLoop;  

```

### iterate

跳到循环开头,再次循环,一般用在loop,while,repeat中

```mysql
iterate lable

# 实例
iterate addLoop;
```

### repeat

带有条件的循环,直到表达式为真退出循环

```mysql
[repeat_label:] repeat
  statement_list;
until cndition;
end repeat [repeat_label]

# eg
declare num int default 0;
repeatLable:repeat
set num=num+1;
until num>10
end repeat repeatLable;
```

# 视图

含义:是一张虚拟表,都一张或多张表导出,也可以基于视图再导出

## 创建

基本语法

```mysql
create [or replace] [algorithm={undefined|merge|temptable}]
view viewName [(columnsList)]
as selectStatement 
[with [caseded|local] check option]
```

其中,create表示新建视图,replace表示替换原有视图

algorithm表示视图选择的算法,undefined表示自动选择算法,merge表示将使用的视图语句与视图定义结合起来,使得视图定义的一部分语句取代语句对应的部分,temptable将视图结果存入临时表,用临时表来执行语句

with [caseded|local] check option表示视图在更新时保证在视图的权限范围之内,caseded为默认值,表示视图在更新时需要满足所有视图与表的条件,local表示视图更新时满足视图定义的条件即可.

```mysql
# 单表
CREATE VIEW emp_view AS 
SELECT `id`,`name`,IF(`status`=1,"正常","禁用") AS `status`,`salary` 
FROM `a_emp1`;
# 多表
CREATE VIEW fruit_view AS 
SELECT f.f_id,f.s_id,f.f_name,f.f_price,s.s_name,s.s_city 
FROM `a_friuts` AS f,`a_suppliers` AS s 
WHERE `f`.`s_id` = `s`.`s_id`;
```

## 查看

```mysql
# 查看视图定义
DESCRIBE emp_view;
SHOW CREATE VIEW emp_view;
# 查看视图信息
SHOW TABLE STATUS LIKE "emp_view";
# 查看information_schema中所有的视图
SELECT * FROM information_schema.`VIEWS`;
```

## 修改

```mysql
CREATE OR REPLACE emp_view AS SELECT * FROM `a_emp1`;
# or
ALTER VIEW emp_view AS SELECT * FROM `a_emp1`;
```

## 更新

更新视图是指通过视图来插入,修改,删除数据,在视图上对数据进行的一系列更新操作都会转到基本表上.更新方法有三种INSERT,UPDATE,DELETE,使用方法与操作基本表一致.

```mysql
update `emp_view` set `status`="禁用" where `id`=3;
insert into `emp_view` (`name`,`status`,`salary`) values ("jhon","正常",5478);
delete from `emp_view` where `status`="禁用";
```

当视图中有以下内容时,更新操作不会执行:

* 视图中不包含基表中被定义为非空的字段;
* 在定义视图的select语句后的字段列表中使用了数学表达正式;
* 在定义视图的select语句后的字段列表中使用了聚合函数;
* 在定义视图的select语句中使用了distinct,union,top,group by或者having子句.

## 删除

```mysql
drop view if exists emp_view;
```

# 触发器

触发器是特殊的存储过程,不需要使用call来调用,只要一个预定义事件发生,就会自动调用,事件包括insert,update,delete.

## 创建

```mysql
create trigger triggerName triggerTime triggerEvent on tableName for each row trigger_stmt;
```

triggerName:触发器名

triggerTime:触发时机,before/after

triggerEvent:触发事件,insert/update/delete

tableName:标识触发器的表名

trigger_stmt:触发执行的语句

```mysql
# 定义
CREATE TRIGGER sumTrigger BEFORE INSERT ON `a_num1` FOR EACH ROW SET @sum = @sum+new.num;
# 触发
SET @sum=0;
INSERT INTO `a_num1` VALUES (14);
SELECT @sum;
```

**在触发器中,insert中new表示新插入的那条数据,update中new表示修改之后的那条数据,delete中old表示被删除的那条数据,update中old表示原来还未修改的那条数据 **

可以使用begin与end创建多个执行语句的触发器

```mysql
# 定义
DELIMITER //
CREATE TRIGGER mulTrigger BEFORE INSERT ON `a_num1` FOR EACH ROW 
BEGIN
SET @newNum = new.num;
DELETE FROM `a_num2` WHERE `num`=new.num;
END
//
DELIMITER
# 触发
SET @sum=0;
SET @newNum = -1;
INSERT INTO `a_num1` VALUES (15);
SELECT @sum,@newNum;
```

## 查看

```mysql
show triggers;
```

## 删除

```mysql
drop trigger a_num1.mulTrigger;
```

# 用户管理

## 登录

```mysql
# -h 主机名,默认localhost
# -u 用户名
# -p 密码,如果有密码,-p与密码连着输入,不能有空格
# -P 端口,默认3306
# 可以在命令的最后指定数据库名称
mysql -h localhost -u userName -ppwd -P 3360 DBName
```

## 添加用户

```mysql
CREATE USER "shooter"@"%" IDENTIFIED BY "123";
```

用户名为shooter,密码为123,%表示任意ip可以登录,如果改成192.168.1.%表示192.168.1开头的ip可以登录,也该已改成任意具体的ip以限制登录的ip

使用create user 创建的用户没有任何权限,需要用grant来赋予权限.

```mysql
# 语法
# privileges权限,有SELECT，INSERT，UPDATE,如果赋予所有权限,可以使用all
# db 数据库名,*表示所有
# table 数据表名,*表示所有
# user 用户名
# host 登录的主机,同create user中的host
# pwd 密码,同create user中的pwd
grant privileges on db.table to user@host IDENTIFIED BY "pwd";

# eg
grant select,insert,update on test.* to "alex"@"localhost" IDENTIFIED by "didadi";
```

## 删除

```mysql
# 删除一个用户及其权限
drop user "alex"@"localhost";
# 删除所有授权表的账户权限记录
drop user;
```

## 修改密码

root用户修改自己的密码

```mysql
# -h 对应的哪个主机,默认localhost 
# -p 第一个为当前密码,第二个为新密码
mysqladmin -u root -h localhost -p password "newpwd";
```

也可以直接修改user表

```mysql
update mysql.user set authentication_string=password("newpwd") where User="root" and Host="localhost";
# 再执行flush privileges来重载权限
```

也可以通过set来修改自己的密码

```mysql
# 修改自己的密码,针对root用户与普通用户都管用
set password=password("newpwd")
# root用户修改普通鱼护的密码
set password for "username"@"host"=password("newpwd")
```

修改普通用户密码

```mysql
# update
update mysql.user set password=password('newpwd') where User="username" and Host="localhost";
# grant
grant usage on *.* to 'username'@'localhost' indentified by 'newpwd';
```

## root用户密码丢失找回密码

* 使用--skip-grant-tables取消权限验证的方式启动mysql

	```mysql
	mysqld --skip-grant-tables
	# linux中
	mysqld_safe --skip-grant-tables user=mysql
	```

* 登录并重置密码

	```mysql
	# 登录
	mysql -u root;
	# 修改密码
	update mysql.user set password=password('newpwd') where User="root" and Host="localhost";
	# 加载权限
	flush privileges;
	```

	## 权限回收

	```mysql
	revoke update on databaseName.* from "username"@"localhost";
	```

	## 权限查看

	```mysql
	SHOW GRANTS FOR 'shooter'@'%';
	```

	

# 数据备份与恢复

## 备份

```mysql
# 使用mysqldump
mysqldump -u root -p root test > /usr/local/src/test.sql;
```

## 数据恢复

```mysql
mysql -u root -p < /usr/local/src/test.sql;
# or 登录mysql执行
source /usr/local/src/test.sql;
```

## 数据库迁移

```mysql
# 在hostA中将数据库dataName迁移至hostB中(mysql相同的版本)
mysqldump -uroot -h hostA -ppwd dataName | mysql -h hostB -uroot -ppwd2;
```

## 导出文本文件

```mysql
# mysqldump命令 将test数据库中的a_person表数据导入到/data/backup目录下
mysqldump -T /data/backup test a_person -u root -p;
# mysql命令 将test数据库中的a_person表数据导入到/data/backup/person.text文件中
mysql -u root -p --excute="select * from a_person;" > /data/backup/person.text
```

## 导入文本文件

```mysql
load data infile " /data/backup/person.text" into table test.a_person;
```

# 日志

## 二进制日志

主要记录mysql数据库的变化,包含了所有更新了数据或者已经潜在更新了数据的语句.使用二进制日志的作用是尽可能大的恢复数据可.

### 配置

在mysql配置文件中如下配置:

```mysql
[mysqld]
# 开启日志
log-bin
# 定义清除过期日志时间,默认值0,表示不自动删除
expire_log_days=10
# 定义单个日志文件大小
max_binlog_size=100M
```

使用命令:`SHOW VARIABLES LIKE "log_%";`查看日志配置情况

查看日志列表:`SHOW BINARY LOGS;`

> 删除

```mysql
# 删除所有二进制日志
reset master;
# 删除创建时间比binlog.000003早的日志
purge master logs to "binlog.000003";
# 删除20200101之前创建的日志文件
purge master logs before "20200101";
```

### 使用二进制日志恢复数据库

基本语法:

```mysql
# option 选项:--start-date指定数据库恢复的起始时间,--stop-date指定数据库恢复的结束时间
#
mysqlbinlog [option] filename | mysql -uusername -ppwd
# 恢复20200101之前的数据操作
mysqlbinlog --stop-date="2020-01-01 00:00:00" /usr/bin/mysql/binlog.000003 | mysql -uroot -ppwd;
```

## 慢查询日志

记录查询时长超过指定时间的日志,默认是关闭的

### 配置

```mysql
[mysqld]
# 开启日志
log-slow-queries
# 设置超时时间10秒
long_query_time=10
```

# 主从复制

指从主服务器(master)将数据复制到一台或者多台从服务器(slave)上的过程,主要是讲主服务器的操作通过二进制日志传到从服务器,然后从服务器重新执行日志的操作,达到同步的效果.

```mysql
# 主服务器设置serveid与开启二进制日志
[mysqld]
server-id=100
log-bin=mysql-bin
# 重启mysql,设置同步账号与权限
service mysql restart

create user 'slave'@'%' identified by '123456';
GRANT REPLICATION SLAVE, REPLICATION CLIENT ON *.* TO 'slave'@'%';
privileges flush;

# 查看主从状态
show master status;
```

```mysql
# 从服务器设置serverid与中继日志
[mysqld]
server-id=100
#开启二进制日志,以后可作为主服务器
log-bin=mysql-slave-bin
# 开启中继日志
relay-log=edu-mysql-relay-log
# 跳过错误设置,可以是错误码,用,隔开,all跳过所有的错误
slave-skip-errors=all
# 设置需要复制或者忽略的数据库
replicate-do-db=test2
replicate-ignore-db=test1
# 设置需要复制或者或略的数据表
replicate-do-table=test1.user
replicate-ignore-table=test2.user

# 添加主服务器信息
change master to master_host='127.0.0.1', master_user='slave', master_password='123456', master_port=3307, master_log_file='mysql-bin.000001', master_log_pos= 154, master_connect_retry=30;
# 查看主从设置
show slave status;
# 开启主从复制
start slave;


# 解除主从复制
stop slave;
reset slave all;
```

# mycat

## 配置文件修改

### 修改conf/server.xml

```xml
<!-- 配置用户数据库数据 用户名不可谓mycat,会报错 --> 
<user name="root" defaultAccount="true">
     <property name="password">root</property>
     <property name="schemas">TESTDB</property>
     <property name="defaultSchema">TESTDB</property>
  <!--No MyCAT Database selected 错误前会尝试使用该schema作为schema，不设置则为null,报错 -->
  <!-- 表级 DML 权限设置 -->
  <!--
      <privileges check="false">
         <schema name="TESTDB" dml="0110" >
              <table name="tb01" dml="0000"></table>
              <table name="tb02" dml="1111"></table>
         </schema>
       </privileges>
   -->
</user>
<user name="user">
		<property name="password">user</property>
		<property name="schemas">TESTDB</property>
         <!-- 设置mycat对TESTDB是否只有只读权限 -->
		<property name="readOnly">true</property>
		<property name="defaultSchema">TESTDB</property>
</user>
```

### 配置conf/schema.xml文件

```xml
<?xml version="1.0"?>
<!DOCTYPE mycat:schema SYSTEM "schema.dtd">
<mycat:schema xmlns:mycat="http://io.mycat/">
        <schema name="TESTDB" checkSQLschema="true" sqlMaxLimit="100" dataNode="dn1">
        </schema>
        <dataNode name="dn1" dataHost="host1" database="test" />
    <!-- balance读写分离配置:0不读写分离,所有的请求都到写主机上,1:所有的查询语句都发送到读与写的备机上,2:所有查询语句随机分配到所有读写的主机上,3:查询都发送到读主机,写都发送到写主机上-->
    <!-- writeType多主多从时,0:默认写到第一个writehost,第一个weritehost挂了后自动切换到下一个writehost,1:多个writehost随机切换-->
        <dataHost name="host1" maxCon="1000" minCon="10" balance="0"
                          writeType="0" dbType="mysql" dbDriver="native" switchType="1"  slaveThreshold="100">
                <heartbeat>select user()</heartbeat>
                <!-- can have multi write hosts -->
                <writeHost host="master1" url="192.168.1.6:3307" user="root" password="123456">
                  <readHost host="slave1" url="192.168.1.6:3308" user="root" password="123456" />
                  <readHost host="slave2" url="192.168.1.6:3309" user="root" password="123456" />
                </writeHost>
                <!-- <writeHost host="hostM2" url="localhost:3316" user="root" password="123456"/> -->
        </dataHost>
</mycat:schema>
```

### 配置conf/rule.xml文件

```xml

```

### 测试登陆mycat

```sh
# 配置好并启动之后可以像访问mysql一样访问mycat,端口一般是8066,维护端口一般是9066
mysql -umycat -p123456 -h 192.168.1.6 -P 8066
```

### 分库配置(schema.xml)

```xml
<?xml version="1.0"?>
<!DOCTYPE mycat:schema SYSTEM "schema.dtd">
<mycat:schema xmlns:mycat="http://io.mycat/">
        <schema name="TESTDB" checkSQLschema="true" sqlMaxLimit="100" dataNode="dn1">
            <!--分表,customer表会创建到dn2节点上,其他表还是在dn1上-->
            <table name="customer" dataNode="dn2"></table>
        </schema>
        <dataNode name="dn1" dataHost="host1" database="test" />
        <dataNode name="dn2" dataHost="host2" database="test" />
    <!-- balance读写分离配置:0不读写分离,所有的请求都到写主机上,1:所有的查询语句都发送到读与写的备机上,2:所有查询语句随机分配到所有读写的主机上,3:查询都发送到读主机,写都发送到写主机上-->
    <!-- writeType多主多从时,0:默认写到第一个writehost,第一个weritehost挂了后自动切换到下一个writehost,1:多个writehost随机切换-->
        <dataHost name="host1" maxCon="1000" minCon="10" balance="0"
                          writeType="0" dbType="mysql" dbDriver="native" switchType="1"  slaveThreshold="100">
                <heartbeat>select user()</heartbeat>
                <!-- can have multi write hosts -->
                <writeHost host="master1" url="192.168.1.6:3307" user="root" password="123456">
                  <readHost host="slave1" url="192.168.1.6:3308" user="root" password="123456" />
                  <readHost host="slave2" url="192.168.1.6:3309" user="root" password="123456" />
                </writeHost>
                <!-- <writeHost host="hostM2" url="localhost:3316" user="root" password="123456"/> -->
        </dataHost>
    	<!--dn2的配置-->
        <dataHost name="host2" maxCon="1000" minCon="10" balance="0"
                              writeType="0" dbType="mysql" dbDriver="native" switchType="1"  slaveThreshold="100">
                    <heartbeat>select user()</heartbeat>
                    <!-- can have multi write hosts -->
                    <writeHost host="master21" url="192.168.1.6:33010" user="root" password="123456">
                      <readHost host="slave21" url="192.168.1.6:3311" user="root" password="123456" />
                    </writeHost>
                    <!-- <writeHost host="hostM2" url="localhost:3316" user="root" password="123456"/> -->
            </dataHost>
</mycat:schema>
```

### 水平分表(schema.xml)

```xml
<?xml version="1.0"?>
<!DOCTYPE mycat:schema SYSTEM "schema.dtd">
<mycat:schema xmlns:mycat="http://io.mycat/">
        <schema name="TESTDB" checkSQLschema="true" sqlMaxLimit="100" dataNode="dn1">
            <!--node:节点,可以多个-->
            <!--rule:拆分规则,如订单表可以根据用户id来拆分-->
            <table name="orders" dataNode="dn1,dn2" rule="order_rule">
                <!-- ER表,如订单表与订单详情表,订单表对应的订单详情应该在同一处 -->
                <!--name:关联的表名-->
                <!--joinKey:子表的关联字段,此处为order_details表里面的order_id-->
                <!--parentKey:父表的关联字段,此处为orders表里面的id字段-->
              <childTable name="order_details" primaryKey="id" joinKey="order_id" parentKey="id"> </childTable>
            </table>
            <!--根据约定范围分片-->
            <table name="payment_info" dataNode="dn1,dn2" rule="auto-sharding-long"></table>
            <!--根据日期分片-->
            <table name="login_logs" dataNode="dn1,dn2" rule="sharding-by-date"></table>
            
            
            <!-- 全局表,数据量小,不经常变动,每个节点的表都与之有关联,但是只从一个节点获取就行,如订单状态表 -->
            <!--type:global设置为全局表-->
            <table name="order_status" primaryKey="id" dataNode="dn1,dn2" type="global"></table>
        </schema>
        <dataNode name="dn1" dataHost="host1" database="test" />
        <dataNode name="dn2" dataHost="host2" database="test" />
        <dataHost name="host1" maxCon="1000" minCon="10" balance="0"
                          writeType="0" dbType="mysql" dbDriver="native" switchType="1"  slaveThreshold="100">
                <heartbeat>select user()</heartbeat>
                <!-- can have multi write hosts -->
                <writeHost host="master1" url="192.168.1.6:3307" user="root" password="123456">
                  <readHost host="slave1" url="192.168.1.6:3308" user="root" password="123456" />
                </writeHost>
        </dataHost>
    	<!--dn2的配置-->
        <dataHost name="host2" maxCon="1000" minCon="10" balance="0"
 writeType="0" dbType="mysql" dbDriver="native" switchType="1"  slaveThreshold="100">
                    <heartbeat>select user()</heartbeat>
                    <!-- can have multi write hosts -->
                    <writeHost host="master21" url="192.168.1.6:33010" user="root" password="123456">
                    </writeHost>
            </dataHost>
</mycat:schema>
```

rule.xml

```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE mycat:rule SYSTEM "rule.dtd">
<mycat:rule xmlns:mycat="http://io.mycat/">
    <!--订单拆分规则-->
	<tableRule name="order_rule">
		<rule>
            <!--表的字段名-->
			<columns>customer_id</columns>
            <!--拆分的逻辑算法,下面的function里面-->
			<algorithm>mod-long</algorithm>
		</rule>
	</tableRule>
    <!--订单拆分规则-->
    <tableRule name="order_rule">
		<rule>
            <!--表的字段名-->
			<columns>customer_id</columns>
            <!--拆分的逻辑算法,下面的function里面-->
			<algorithm>mod-long</algorithm>
		</rule>
	</tableRule>
    <!--按范围拆分-->
    <tableRule name="auto-sharding-long">
		<rule>
            <!--表的字段名-->
			<columns>pay_amount</columns>
            <!--拆分的逻辑算法,下面的function里面-->
			<algorithm>rang-long</algorithm>
		</rule>
	</tableRule>
    <!--按范日期分-->
    <tableRule name="sharding-by-date">
		<rule>
            <!--表的字段名-->
			<columns>login_date</columns>
            <!--拆分的逻辑算法,下面的function里面-->
			<algorithm>login-by-date</algorithm>
		</rule>
	</tableRule>
    
    
    <function name="mod-long" class="io.mycat.route.function.PartitionByMod">
		<!-- how many data nodes -->
        <!-- 取模的节点数 -->
		<property name="count">2</property>
	</function>
    <function name="rang-long" class="io.mycat.route.function.AutoPartitionByLong">
        <!--autopartition-long.txt内容-->
        <!-- 0-10=0 -->
        <!-- 11-20=1 -->
		<property name="mapFile">autopartition-long.txt</property>
        <!--设置默认节点-->
        <property name="defaultNode">0</property>
	</function>
    <function name="login-by-date" class="io.mycat.route.function.PartitionByDate">
        <!--字段格式 -->
        <property name="dateFormat">yyyy-MM-dd</property>
		<property name="sNaturalDay">0</property>
        <!-- 开始时间 -->
		<property name="sBeginDate">2014-01-01</property>
        <!-- 结束时间 -->
		<property name="sEndDate">2014-01-31</property>
        <!-- 分区间隔,即每隔几天一个分区 -->
		<property name="sPartionDay">10</property>
	</function>
</mycat:rule>
```

### 常用的分片规则

```
1.取模
2.枚举(如根据地区来分片)
3.约定范围
4.按照日期
```

### 全局序列

> 分库分表的情况下,无法保证主键自增的唯一性,可使用全局序列来实现主键唯一

实现方式有(推荐数据库方式):

1. 本地文件(mycat挂了之后数据会丢失)
2. 数据库
3. 时间戳(字段内容比较长)
4. 自定义生成逻辑

配置流程:

1. 选择一个节点生成相应的数据表(一般默认dn1)

```mysql
# 建表
DROP TABLE IF EXISTS MYCAT_SEQUENCE;
CREATE TABLE `MYCAT_SEQUENCE` (
  `NAME` varchar(50) NOT NULL comment  "名称",
  `current_value` int(11) NOT NULL comment "当前值",
  `increment` int(11) NOT NULL DEFAULT '100' comment "步长",
  PRIMARY KEY (`NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
# 插入数据
INSERT INTO MYCAT_SEQUENCE(name,current_value,increment) VALUES ('mycat', 10000, 100);
# 创建存储过程
## 获取当前sequence的值
DROP FUNCTION IF EXISTS mycat_seq_currval;
DELIMITER $
CREATE FUNCTION mycat_seq_currval(seq_name VARCHAR(50)) RETURNS varchar(64)     CHARSET utf8
DETERMINISTIC
BEGIN
DECLARE retval VARCHAR(64);
SET retval="-999999999,null";
SELECT concat(CAST(current_value AS CHAR),",",CAST(increment AS CHAR)) INTO retval FROM MYCAT_SEQUENCE WHERE name = seq_name;
RETURN retval;
END $
DELIMITER ;
## 设置sequence值
DROP FUNCTION IF EXISTS mycat_seq_setval;
DELIMITER $
CREATE FUNCTION mycat_seq_setval(seq_name VARCHAR(50),value INTEGER) RETURNS     varchar(64) CHARSET utf8
DETERMINISTIC
BEGIN
UPDATE MYCAT_SEQUENCE
SET current_value = value
WHERE name = seq_name;
RETURN mycat_seq_currval(seq_name);
END $
DELIMITER ;
## 获取下一个sequence值
DROP FUNCTION IF EXISTS mycat_seq_nextval;
DELIMITER $
CREATE FUNCTION mycat_seq_nextval(seq_name VARCHAR(50)) RETURNS varchar(64)     CHARSET utf8
DETERMINISTIC
BEGIN
UPDATE MYCAT_SEQUENCE
SET current_value = current_value + increment WHERE name = seq_name;
RETURN mycat_seq_currval(seq_name);
END $
DELIMITER ;
```

2. 配置mycat的设置

```sh
# server.xml,0:本地文件,1数据库,2时间戳
<property name="sequnceHandlerType">1</property>
# schema.xml
<table name="mycat_sequence" primaryKey="name" dataNode="dn1"/>
#修改Mycat 配置文件 sequence_db_conf.properties，添加MYCAT=dn1
GLOBAL=dn1
COMPANY=dn1
CUSTOMER=dn1
ORDERS=dn1
MYCAT=dn2
```

3. 重启mycat
4. 插入数据格式如下:

```mysql
insert into test(id,name) values(next value for MYCATSEQ_MYCAT,'name');
```



3. 房贷首付

   



