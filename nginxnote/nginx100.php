<?php
/**
 *  1-10 ：
 *      nginx的五个优点:
 *       1. 高并发
 *          如果是简单的静态链接 可以达到100w的rps
 *       2. 可拓展性好
 *          模块化的设计
 *       3. 高可靠
 *          可以在服务器上持续不间断的运行数年
 *       4. 热部署
 *          可以在不重启服务的情况下 升级nginx
 *       5. bsd许可证
 *
 *
 *      nginx的四个组成部分:
 *       nginx二进制可执行文件
 *       nginx.conf 配置文件
 *       access.log 日志文件
 *       error.log 错误日志
 *
 *      nginx命令行 :
 *       nginx -c      人为指定默认的配置文件
 *       nginx -g      人为指定配置命令
 *       nginx -s  stop     立即停止所有服务
 *       nginx -s  quit     优雅的停止所有服务
 *       nginx -s  reload   重载配置文件
 *       nginx -s  reopen   重新开始记录日志文件
 *       nginx -t           检查配置文件语法是否有误
 *       nginx -v           检查nginx的版本
 *
 *     热部署 :
 *       只需要替换nginx的二进制文件
 *      1. 备份之前的文件  cp sbin/nginx  nginx.old  并且拷贝新的二进制文件到目录中
 *      2. Kill -USR2 [master_pid]          告诉nginx要热部署了,开启新的master进程
 *      3. kill -WINCH 13195                链接全部转入新的master后， 关闭老的master的子进程
 *     
 *     日志切割 ：
 *      1. 将老的日志文件进行备份
 *      1. nginx -s  reopen  新开一个配置文件实现日志切割
 */


/**
 *  11-20 ：
 *      最基本的区别：alias指定的目录是准确的，root是指定目录的上级目录，并且该上级目录要含有location指定名称的同名目录。另外，根据前文所述，使用alias标签的目录块中不能使用rewrite的break。

        (1) . alias虚拟目录配置中，location匹配的path目录如果后面不带"/"，那么访问的url地址中这个path目录后面加不加"/"不影响访问，访问时它会自动加上"/"；
        但是如果location匹配的path目录后面加上"/"，那么访问的url地址中这个path目录必须要加上"/"，访问时它不会自动加上"/"。如果不加上"/"，访问就会失败！
        (2) . root目录配置中，location匹配的path目录后面带不带"/"，都不会影响访问。

        所以，一般情况下，在nginx配置中的良好习惯是：
        1）在location /中配置root目录；
        2）在location /path中配置alias虚拟目录。
 *
 *
 *  配置静态文件 :
 *       location / {
        root   /usr/local/nginxwww;
        index  index.html index.htm;
        }

 *
 *  开启gzip压缩:
 *      gzip on;     开启gzip压缩
 *      gzip_min_length 1;    如果小于1个字节就不进行压缩了
 *      gzip_comp_level 2;    压缩级别
 *      gzip_types ..;        压缩类型
 *
 *  显示配置静态路由下面的所有的目录
 *      location / {
            root /usr/location/nginxwww;
 *          autoindex on;                      显示整个目录
 *          set $limit_rate 1k;                限制传输速度到浏览器
 *      }
 *
 *
 *
 *   配置访问日志:
 *      http{
           log_format main ...;                   配置日志的格式化信息
 *
           access_log   log/access.log  main;     日志位置  和格式化的段
 *                                                (可以放在http下和server下)
 *      }
 *
 *
 *
 *
 *  12： nginx 搭建一个反向代理服务器
 *      水平拓展 ，可以由一台服务器分发至多台服务器
 *
 *      只允许本机进行访问
 *      server {
            listen  127.0.0.1:80;
 *      }
 *
 *      配置反向代理服务器
 *      http{
            upstream local {
 *              server 127.0.0.1:80;   可以配置n台服务器
 *          }
 *          server {
                server_name geek.taohui.pub;
 *              listen 80;
 *              location / {
                    proxy_set_header Host $host;                客户端的host
 *                  proxy_set_header X-Real_IP $remote_addr;    客户端的ip给上游服务器
 *                  proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;        
 *                  proxy_pass http://local;                    代理到刚刚配置的上游服务里
 *              }
 *          }
 *      }
 *
 *
 *
 *
 *
 *
 *
 */


