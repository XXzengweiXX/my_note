# 核心模块说明(/etc/nginx/nginx.conf)

```ng
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