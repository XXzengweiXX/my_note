# Image

## 查看

```shell
# 查看镜像
docker image ls
docker iamges
# 删除镜像
docker image rm 
# 拉取镜像
# 拉取tag为12.04的ubuntu镜像
docker pull ubuntu:12.04
```

## 搜索

```shell
# --filter:过滤条件
# --limit:限制结果个数
# --no-trunc:不截断输出结果
docker serach [option] keyword
docker serach --filter=is-official=true --limit 10 ubuntu
```



## 创建镜像

### 修改已有的镜像

```shell
# 进入容器
docker attach a4df51
# 在容器中进行修改操作
apt-get update
apt-get install vim
# 退出容器
exit;
# 创建新镜像
# -m:指定说明信息; -a:指定更新用户的信息;之后是原容器id;最后是指定仓库名与tag信息;
# 成功之后返回镜像id
 docker commit -m "add vim to ubuntu" -a "ubuntu_with_vim" 3f6c1e204727 mylocal/ubuntu:v2

```

### 利用dockerfile来创建镜像

```shell
# 创建dockerfile文件,并编写,在dockerfile文件中可以用#添加注释
# 可以视同add命令复制本地文件到镜像:ADD localApp /var/www
# 使用expose向外开放端口: EXPOSE 80

# this is comment
FROM ubuntu:latest
MAINTAINER Docker xxoo <email@163.com>
RUN apt-get update
RUN apt-get install nginx

# 执行命令
# -f:指定dockerfile文件路径;-t:指定文件tag;
 docker build -f .\Dockerfile -t mylocal/ubuntu_nginx:v2 .

```

##  修改镜像标签

```shell
docker tag fdc015074ad2 mylocal/ubuntu_nginx:dev
```

## 详细信息

``` shell
# 需要在最后加上标签v2,否则会报没有文件的错误
docker [image] inspect mylocal/ubuntu_nginx:v2
# 只获取某一项镜像信息,如Architecture
docker [image] inspect -f {{".Architecture"}} mylocal/ubuntu_nginx:v2
```



## 上传镜像

```shell
docker push mylocal/ubuntu_nginx
```

## 导入与导出

```shell
# 导出
docker save -o ubuntu_nginx.tar mylocal/ubuntu_nginx
# 导入
docker load --input ubuntu_nginx.tar
docker load < ubuntu_nginx.tar
```

## 删除

```shell
# 删除镜像之前先删除调所有依赖次镜像的容器
docker rmi image_name/image_id

# 以清理所有未打过标签的本地镜像
docker rmi $(docker images -q -f "dangling=true")
docker rmi $(docker images --quiet --filter "dangling=true")
# 清理临时或者没有被使用的镜像
# -a:删除所有的无用镜像,不仅是临时镜像;-f:强制删除,不提示确认;--filter filter:添加过滤条件
docker image purge
docker iamge purge -f
```



# Containner

## 查看

```shell
# 查看启动的容器
docker ps
# 查看所有的容器
docker ps -a
# 查看详情
docker container inspect first_ubuntu
# 查看容器内的进程
docker top first_ubuntu
```

## 运行

```shell
# -d,--detach=true/false:是否后台运行容器
# --expose=[]:指定容器暴露的端口或者端口范围
# -p:映射本地端口到容器端口   -p 80:80
# --restart="no":容器重启策略,包括no,on-fature[:max-try],always,unless-stopped
# -v:挂载宿主机文件到容器内   -v /local/data:/container/data
# -e:指定容器内环境变量  -e MYSQL_PWD=123456
# -h:指定容器内主机名  -h localhost
# --name="":指定容器别名 --name="ubuntu18.2"
# -m,--memery="":限制容器的使用内存,单位是b,k,m,g等 -d 500m
# -t:分配一个伪终端并绑定到容器的标准输入上
# -i:让容器的标准输出保持打开


# 创建一个处于停滞状态的容器,之后可以通过start来启动它
docker create -it ubuntu:latest

# 启动容器
# 创建一个ubuntu容器,让其中运行bash应用
# -v:绑定一个卷 -v local/data:/var/data(将宿主机的local/data映射到容器的/var/www上)
docker run -t -i ubuntu /bin/bash

# 启动一个停止了的容器
docker start container_name/id
# 重启
docker restart container_name/id
```

## 容器输出查看

```shell
# -details:打印详细信息 docker logs -details a45dfds444 
# -f,-follow:持续输出 docker logs -f a45dfds444 
# -since string:输出从某个时间开始的日志
# -tail int:输出最近的n条记录 docker logs -n 100 a45dfds444 
docker [container] logs container_name/container_id
```



## 停止

```shell
# 暂停
docker pause container_name/container_id
# 恢复暂停状态的容器
docker unpause container_name/container_id

# 终止
docker stop container_name/id
```

## 清理容器

```shell
# 清理处于停止状态的容器
docker container purge
```

## 删除

```shell
# -f:强制停止并删除正在运行的容器
# -l:删除容器的链接,但是保存容器
# -v:删除容器挂载的数据卷
docker [container] rm container_name/container_id
```



## 进入容器

```shell
# attach:进入容器,但是如果有多个窗口使用该命名,所有窗口的画面会同步,当某个窗口阻塞时,其他窗口也无法执行操作
docker attach image_id

# exec:可在运行中容器内执行任何命令
# -d:后台运行
# -e:指定环境变量
# -i:打开标准输入
# -t:分配伪终端
docker exec -it 
```

## 退出容器

```shell
# 退出容器,不关闭容器
ctl+q+p
# 退出并关闭容器
exit
```

## 导入与导出

```shell
# 导出
docker export container_id > container_name.tar
# eg: docker export s4dfdsf54444 > ubuntu.tar
docker [container] export -o container_name.tar container_name
# eg docker container export -o ubuntu.tar ubuntu

# 导入,可以从容器快照中导入,也可以通过url或者某个目录导入
cat ubuntu.tar | docker import - myslocql/ubuntu:v2

docker import http://www.example/container.tgz myslocql/ubuntu
docker import ubuntu.tar - mylocal/ubuntu:v2
```

##  其他命令

### 复制

```shell
# 将本地文件data复制到test容器的tmp文件下
docker container cp data test:/tmp/
```

### 查看更改

```shell
# 查看容器内文件的更改情况
docker container diff mysql5.7_master
```

### 查看端口映射

```shell
docker container port mysql5.7_master
```

# docker仓库

仓库（Repository ）是集中存放镜像的地方，又分公共仓库和私有仓库.

注册服务器(Registy)是存放仓库的具体服务器， 个注册服务器上可以有多个仓库，而每个仓库下面可以有多个镜像.

# 数据管理

在生产环境中使用 Docker ，往往需要对数据进行持久化，或者需要在多个容器之间进行数据共享，这必然涉及容器的数据管理操作

容器中的管理数据主要有两种方式:

* 数据卷:容器内数据直接映射到本地主机环境
* 数据卷容器:使用特定容器维护数据卷

## 数据卷

数据卷是一个可供容器使用的特殊目录，它将主机操作系统目录直接映射进容器，类似于 Linux 中的 mout 行为.

特性:

1. 数据卷可以在容器之间共事和重用，容器间传递数据将变得高效与方便;

2. 对数据卷内数据的修改会立马生效，无论是容器内操作还是本地操作;

3. 对数据卷的更新不会影响镜像，解摘开应用和数据;

4. 数据卷会一直存在 ，直到没有容器使用，可以安 地卸载它.

	### 创建数据卷

	```shell
	# 在本地创建一个名为my_volume的数据卷
	docker volume create -d local my_volume
	```

	### 查看详情

	```shell
	docker volume inspect my_volume
	```

	### 列表

	```shell
	docker volume ls
	```

	### 删除

	```shell
	#删除
	docker volume my_volume
	# 清理无用的数据卷
	docker volume prune
	```

	### 绑定数据卷

	​	在创建容器时将主机本地的任意路径挂载到容器内作为数据卷，这种形式创建的数据卷称为绑定数据卷.

	​	在docker [container] run命令时,可以使用mount选项来使用数据卷.mount支持三种数据类型:

	1. volume:普通卷,映射到主机的/var/lib/docker/volumes路径下
	2. bind:绑定数据卷,映射到主机指定路径下
	3. tmpfs:临时数据卷,存在于内存中

	```shell
	# 创建一个web容器,并把/webapp挂载到容器的/opt/webapp下
	docker run -d -P --name web --mount type=bind,source=/webapp,distination=/opt/webapp traning/webapp python app.py
	# 上面的命令等同于下面的命令
	docker run -d -P --name web -v /webapp:/opt/webapp traning/webapp python app.py
	# 注:本地路径必须是绝对路径
	```

## 容器数据卷

​	如果用户需要在多个容器之间共享一些持续更新的数据，最简单的方式是使用数据卷容器.数据卷容器也是一个容器，但是它的目的是专门提供数据卷给其他容器挂载.

```shell
# 创建一个数据卷容器dbdata,并在其中创建一个数据卷挂载到/dbdata下
docker run -it -v /dbdata --name dbdata ubuntu
# 在其他容器中可以使用--volumes-from来挂载dbdata容器中的数据卷,也可以从其他挂载了数据卷的容器来挂载数据卷
# 多次使用该参数可以从多个容器挂载多个卷
docker run -it --volumes-from dbdata --name db1 ubuntu
```

# dockerfile

Dockerfile 由一行行命令语句组成， 并且支持以＃开头的注释行.

一般而言， Dockerfile 主体内容分为四部分：**基础镜像信息**、 **维护者信息**、 **镜像操作指令**和**容器启动时执行指令**.

1. 主体部分首先使用FROM指令指明所基于的镜像名称
2. 接下来一般是使用LABEL指令说明维护者信息
3. 后面则是镜像操作指令， 例如RUN指令将对镜像执行跟随的命令
4. 最后是CMD指令， 来指定运行容器时的操作命令

## 指令

| 指令         | 说明                                                         | 用法                                                         |
| ------------ | :----------------------------------------------------------- | ------------------------------------------------------------ |
| ARG          | 定义创建镜像过程中使用的变量,可以在执行docker build时用--build-arg赋值 | ARG name[=<default value>],赋值:docker build --build-arg name=value |
| FROM         | 指定所创建镜像的基础镜像,任何 Dockerfile 中第一条指令必须为FROM 指令 | FROM <iamge> [AS <name>]  /  FROM <image>:<tag> [As <name>],eg:FROM ubuntu:latest |
| LABEL        | 为生成的镜像添加元数据标签信息                               | LABLE key1=value1 key2=value2  eg:LABEL author="dadada"      |
| EXPOSE       | 声明镜像内服务监听的端口,并不会自动完成端口映射,可以在启动容器时使用-p来映射端口 | EXPOSE 80 443                                                |
| ENV          | 指定环境变量,可以在启动容器时使用docker run --env <key>=<value>来覆盖 | ENV key1 value1      OR   ENV key1=value1                    |
| ENTRYPOINT   | 指定镜像的默认入口命令,与CMD功能类似,但是docker run命令有操作命令时,ENTRYPOINT里面的命令不会被替换,而是追加 | ENTRYPOINT ["executable","param1","param2",..]               |
| VOLUME       | 创建一个数据卷挂载点,运行容器时可以从本地主机或其他容器挂载数据卷 | VOLUME ["/data"]                                             |
| USER         | 指定运行容器时的用户名或UID                                  | USER daemon                                                  |
| WORKDIR      | 配置工作目录,为后续的RUN,CMD,ENTRYPOINT命令配置工作目录,可以有多个,如果后面的参数是相对路径,则会基于之前配置的路径,就是进入容器后的默认的位置 | WORKDIR /var/www                                             |
| ONBUILD      | 创建子镜像时指定自动执行的操作指令,父镜像被子镜像继承后,父镜像的ONBUILD会被触发执行 | ONBUILD RUN /usr / local/bin/python build --dir / app/src    |
| STOPSIGNAL   | 指定退出的信号值                                             | STOPSIGNAL signal                                            |
| HEALTH CHECK | 配置所启动容器如何进行健康检查                               |                                                              |
| SHELL        | 指定默认shell类型                                            | SHELL [” executable ”,”parameters”]                          |
| RUN          | 运行指定命令                                                 | RUN <COMMAND> 或者 RUN ["executable","param1","param2"]      |
| CMD          | 启动容器时指定默认执行的命令,多个CMD只有最后一个会执行,如果docker run启动命令行有相关命令操作,则CMD会被替换 | CMD command param1 param2  或者 CMD ["executable","param1","param2"] |
| ADD          | 添加内容到镜像,格式为ADD <src> <dest>,该命令将复制指定的<src>路径下内容到容器中的<de st>路径下,会把tar压缩包解压 | ADD /localdata /containerdata                                |
| COPY         | 复制内容到镜像,格式为COPY <src> <dest>,复制本地主机的<src> （为 Dockerfile 所在目录的相对路径，文件或目录）下内容到镜像中的<dest>.COPY与ADD 指令功能类似，当使用本地目录为源目录时，推荐使用 COPY,但是不会解压tar压缩包 | COPY /localdata /containerdata                               |

## 创建镜像

​	编写完成 Dockerfile 之后，可以通过docker build来创建镜像,基本格式为docker build [option] path,该命令会读取指定目录下的Dockerfile文件,并将该路径下所有数据作为上下文（ Context ）发送给 Docker 服务,Docker 服务端在校验 Dockerfile 格式通过后，逐条执行其中定义的指令，碰到 ADD COPY RUN 指令会生成 层新的镜像 最终如果创建镜像成功，会返回最终镜像的 ID

# 实战

## 搭建ssh服务

```shell
# 运行容器
docker run -it --name ubuntu_ssh ubuntu:18.04 /bin/bash
# 安装vim
apt-get update
apt-get install vim
# 替换源
vim /etc/apt/sources.list.d/163.list
# 内容如下
deb http://mirrors.163.com/ubuntu/ bionic main restricted universe multiverse
deb http://mirrors.163.com/ubuntu/ bionic-security main restricted universe multiverse
deb http://mirrors.163.com/ubuntu/ bionic-updates main restricted universe multiverse
deb http://mirrors.163.com/ubuntu/ bionic-proposed main restricted universe multiverse
deb http://mirrors.163.com/ubuntu/ bionic-backports main restricted universe multiverse
deb-src http://mirrors.163.com/ubuntu/ bionic main restricted universe multiverse
deb-src http://mirrors.163.com/ubuntu/ bionic-security main restricted universe multiverse
deb-src http://mirrors.163.com/ubuntu/ bionic-updates main restricted universe multiverse
deb-src http://mirrors.163.com/ubuntu/ bionic-proposed main restricted universe multiverse
deb-src http://mirrors.163.com/ubuntu/ bionic-backports main restricted universe multiverse
# 更新源
apt-get update
# 安装配置ssh服务
apt-get install openssh-server
# 启动ssh服务,必须保证/var/run/sshd目录存在,不存在则需要自己创建  mkdir -p /var/run/sshd
/usr/sbin/sshd -D &
# 查看22端口的监听状况
netstat -tunlp
# 在root用户目录下创建.ssh目录,将公钥信息添加到.ssh目录下的authorized_keys文件中
mkdir /root/.ssh
vim /root/.ssh/authorized_keys
# 创建启动ssh服务的文件run.sh,并赋予执行权限 chmod +x /run.sh
# run.sh文件内容如下
#!/bin/bash
/usr/sbin/sshd -D
# 退出容器
exit
# 保存镜像
 docker commit ubuntu_ssh  sshd:ubuntu
# 使用镜像
docker run -p 10022:22 -d --name my_sshd sshd:ubuntu /run.sh
# 登录
ssh 192.168.2.204 -p 10022
```

## mysql

```shell
# MYSQL_ROOT_PASSWORD:设置root用户密码
docker run -d --name my_mysql -e MYSQL_ROOT_PASSWORD=root_pwd -v /local/mysql/cnf:/etc/mysql/config.d mysql:5.7
```

# 一些指令

```shell
# 删除正在运行的container ; -q 获取容器id
docker rm -f $(docker ps -q)
```

# docker-compose

##  配置文件yml

>以key: value的形式来指定配置信息
>
>多个配置信息用换行与缩进来区分

```yml
version: '3.1'
services: 
  mysql:                            #服务名称
    restart: always                 #设置随着docker一起启动
    image: mysql:5.7                #镜像路径以及tag
    container_name: mysql_5.7        #容器名称
    ports:                          #设置端口映射(宿主机端口:容器端口)
      - 3307:3306
      - 3308:3308
    environment:                    #环境变量设置
      MYSQL_ROOT_PASSWORD: 123456
      TZ: Asia/Shanghai
    volumes:                        #数据卷映射
      - /data/docker/mysql/data:/var/lib/mysql
      - /data/docker/mysql/conf:/etc/mysql/conf.d
  nginx:                            #下一个服务配置
    restart: always
    image: nginx:1.18
    container_name: nginx_1.18
    ports:
      - 8089:80
    volumes:
      - /data/docker/ngix/conf:/etc/nginx/conf
      - /data/docker/nginx/log:/var/log/nginx
```

## 管理命令

> 使用docker-compose命令时,会默认寻找当前目录下的docker-compose.yml文件

```sh
#启动容器
docker-compose up -d
#关闭并删除容器
docker-compose down
#开启|关闭|重启容器
docker-compose start|stop|restart
#查看容器
docker-compose ps
#查看日志
docker-compose logs -f
```

> 使用docker-compose配合Dockerfile构建自定义容器

[docker-compose.yml]()

```yml
version: '3.1'
servcies:
  mysql:
    restart: always
    build:                    #构建自定义镜像
      context: ../            #Dockerfile位置
      dockerfile: Dockerfile  #Dockerfile文件名
    image: mysql:5.7
    container_name: mysql_5.7
    ports:
      - 3308:3306
    enviment:
      TZ: Asia/Shanghai
```

[Dockerfile]()

```
FROM mysql:5.7
RUN echo "build with Dockerfile"
```

