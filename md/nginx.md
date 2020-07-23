# 核心模块说明(/etc/nginx/nginx.conf)

```nginx
user www;#配置用户或者用户组
worker_processes 4;#允许生成的进程数,默认1;
pid /run/nginx.pid;#指定 nginx 进程运行文件存放地址
# 事件模块
events {
   worker_connections 768;#最大连接数
   #multi_accept on;
}
//http模块
http{
	include mime.types; #文件扩展名与文件类型映射表
	default_type application/octet-stream; #默认文件类型，默认为text/plain
	access_log off; #取消服务日志
	
	server{
		listen:80;#监听的端口
		server_name:www.baidu.com;#监听域名
		root:/var/www/laravel/public;#项目根目录
		index:index.php index.html;#开始文件
		location ~*^.+$ { #请求的url过滤，正则匹配，~为区分大小写，~*为不区分大小写 /通配符
		   proxy_pass  http://mysvr;  #请求转向mysvr 定义的服务器列表
           deny 127.0.0.1;  #拒绝的ip
           allow 172.18.5.54; #允许的ip   
           fastcgi_pass 127.0.0.1:9000;#监听php-fpm端口
           #当用户请求 http://localhost/example 时，这里的 $uri 就是 /example。
           #try_files 会到硬盘里尝试找这个文件。如果存在名为 /$root/example（其中 $root 是项目代码安装目录）的文件，就直接把这个文件的内容发送给用户。
           #显然，目录中没有叫 example 的文件。然后就看 $uri/，增加了一个 /，也就是看有没有名为 /$root/example/ 的目录。
           #又找不到，就会 fall back 到 try_files 的最后一个选项 /index.php，发起一个内部 “子请求”，也就是相当于 nginx 发起一个 HTTP 请求到 http://localhost/index.php。
           try_files $uri $uri/ /index.php?$query_string;
		}
	}
}

```

# 负载均衡

1. 轮询(默认)

```nginx
upstream backserver {
    server 192.168.0.14;
    server 192.168.0.15;
}
server {
       listen 80;
        location / {
           proxy_pass http://backserver;
       }
    }
```

2. 比重

> 权重越高，在被访问的概率越大,可能出现丢失登陆信息的问题

```nginx
upstream backserver {
    server 192.168.0.14 weight=3;
    server 192.168.0.15 weight=7;
}
```

3. ip_hash

> 每个请求按访问ip的hash结果分配，这样每个访客固定访问一个后端服务器，可以解决session的问题

```nginx
upstream backserver {
    ip_hash;
    server 192.168.0.14:88;
    server 192.168.0.15:80;
}
```

4. fair

> 按后端服务器的响应时间来分配请求，响应时间短的优先分配。

```nginx
upstream backserver {
    server server1;
    server server2;
    fair;
}
```

5. url_hash

> 按访问url的hash结果来分配请求，使每个url定向到同一个后端服务器，后端服务器为缓存时比较有效。

```nginx
upstream somestream {
    hash $request_uri;
    server 192.168.244.1:8080;
    server 192.168.244.2:8080;
    server 192.168.244.3:8080;
    server 192.168.244.4:8080;
 
}
server {
    listen 8081 default;
    server_name test.csdn.net;
    charset utf-8;
    location /get {
    proxy_pass http://somestream;
 
    }  
}
```

