<?php
/**
 *   2基础篇 ：
 *      2.1 : nginx是什么
 *          nginx是一个开源高性能，可靠的http中间件，代理服务。
 *
 *      2.3 ：为什么选择nginx
 *          1. io多路复用 epoll模型
 *            多个描述符的io操作都能在一个线程内并发交替的顺序完成 ，这就是
 *            io多路复用 ，这里的复用指的是复用同一个线程
 *
 *          2. 什么是epoll
 *             io多路复用的实现方式
 *            select,poll,epoll
 *             select :  应用请求内核 ，等待内核发送read请求可用的信息 。这段时间
 *                  应用是阻塞住的。如果内核发送了read可用，那么应用开始遍历文件
 *                  描述符的列表。 这个时候也是阻塞的
 *
 *             epoll :  1. 每当fd 请求的可用信息就绪就会通过系统的回调函数将fd放入
 *                    效率更高
 *                      2. 最大链接无限制
 *
 *       2.4 ：   为什么选择nginx -- 轻量级
 *             功能模块少
 *             代码模块化
 *
 *       2.5 ：  为什么选择nginx -- cpu亲和(affinity)
 *             为什么需要cpu亲和
 *             将每个进程绑定到cpu的每个核心上  这样可以避免cpu切换带来的性能问题
 *
 *       2.6 ：  为什么选择nginx -- sendfile
 *             正常来说访问一个文件要从内核空间 -- 用户空间 才能访问
 *             静态文件其实不用通过用户空间  就可以进行传输
 *             sendfile 通过
 *
 *       2.9 ： 编译配置参数
 *             nginx -V    查看nginx编译的配置参数
 *          目录参数 :
 *             --prefix=/etc/nginx     nginx的目录
 *             --sbin-path=/usr/sbin/nginx      启动文件目录
 *             --modules-path=/usr/lib64/nginx/modules      模块配置目录
 *             --config-path=/etc/nginx/nginx.conf          配置文件目录
 *             --error-log-path=/var/log/nginx/error.log    错误文件目录
 *             --http-log-path=/var/log/nginx/access.log    访问日志目录
 *             --pid-path=/var/run/nginx.pid                pid文件目录
 *             --lock-path=/var/run/nginx.lock              锁文件目录
 *
 *          执行对应模块时nginx保留的临时文件目录
 *             --http-client-body-temp-path=/var/cache/nginx/client_temp
 *             --http-proxy-temp-path=/var/cache/nginx/proxy_temp
 *             --http-fastcgi-temp-path=/var/cache/nginx/fastcgi_temp
 *             --http-uwsgi-temp-path=/var/cache/nginx/uwsgi_temp
 *             --http-scgi-temp-path=/var/cache/nginx/scgi_temp
 *
 *          设定nginx启动进程的用户和用户组
 *             --user=nginx  --group=nginx
 *
 *          设置额外的参数添加到变量中
 *             --with-cc-opt=parameters
 *
 *          设置额外的参数链接到系统库
 *             --with-ld-opt=parameter
 *
 *      2.10 nginx.conf 的基础配置语法。
 *          额外配置文件 :
 *          http{
 *            ...
 *
 *
 *            include /etc/nginx/conf.d/*.conf;    包含的额外配置文件
 *          }
 *
 *          全局配置 :
 *          user        设置nginx服务的系统使用用户
 *          worker_processes        设置工作进程数
 *          error_log               nginx 的错误配置日志
 *          pid                     nginx 启动时候的pid文件
 *
 *          events配置 :
 *          events{
                worker_connections 1024;  每个进程允许的最大链接数
 *              use  4;                   工作进程数
 *          }
 *
 *
 *          server配置:
 *          server{
                listen 80;
 *              server_name localhost;
 *              location / {
                    root /dir;
 *                  index index.html index.php;
 *              }
 *              error_page  500 502 504  /50x.html;  如果错误的响应码路由到/50x.html 路径中
 *              location = /50x.html {
                    root /root/fault;
 *              }
 *          }
 *
 *
 *      2.11 : nginx的目录和配置语法
 *          http{
                sendfile  on;               是否提高静态文件的传输效率 避开用户空间
 *              keepalive_timeout 65;       设置客户端和服务端的超时时间
 *              include  /etc/nginx/conf.d/*.conf;      设置额外的配置文件
 *          }
 *
 *
 *      2.12 ： http请求
 *
 *
 *      2.13 14： nginx日志
 *          nginx日志类型 : 包括 error.log  access_log
 *          log_format  name;      只能配置到http段中
 *
 *          日志变量 :
 *          HTTP 请求变量 ：arg_PARAMETER , http_HEADER, sent_http_HEADER;
 *          例子  记录请求中的 user-agent ；
 *              $http_user_agent  ;  全是小写和下划线组成的
 *
 *          内置变量 -   Nginx内置的变量
 *
 *          自定义变量
 *
 *      2.15 : nginx 模块讲解
 *          nginx 官方模块
 *          第三方模块
 *
 *      2.16 ： --with-http_stub_status_module  模块详解
 *          该模块主要展示nginx的状态
 *          开启位置 server , location 段中
 *      编译安装:
 *      --prefix=/usr/local/software/nginx-1.15.9  --with-http_stub_status_module
 *      使用:
 *      location /stub {
            stub_status;
 *      }
 *
 *      结果 :
 *      Active connections: 1       目前活跃
        server accepts handled requests
        1 1 2
 *      握手数     链接数     总请求数
        Reading: 0 Writing: 1 Waiting: 0
 *      正在读  正在写  正在等待
 *
 *
 *      2-17 : nginx模块详解  默认模块
 *      --with-http_random_index_module  目录中选择一个随机主页
 *      --prefix=/usr/local/software/nginx-1.15.9 --with-http_stub_status_module  --with-http_random_index_module

 *          默认是关闭的
 *          配置区 ： location
 *      location / {
 *          random_index on;      自动选择文件
 *      }
 *
 *
 *      2-18 : nginx模块详解 sub_module
 *      --with-http_sub_module   http 内容替换
 *
 *      syntax : sub_filter string replacement;
 *      context :  http , server , location
 *      default :  ----- 没有
 *
 *      syntax : sub_filter_last_modified  on |  off ;
 *      default : sub_filter_last_modified off;
 *      context : http , server , location ;
 *      作用: 如果发生更新 返回最新的html代码 没有更新则返回缓存
 *
 *      syntax : sub_filter_once on | off;
 *      default :  sub_filter_once on;
 *      context ： http server location
 *      作用: 匹配所有html代码里的第一个字符串 还是匹配所有  默认是匹配第一个
 *
 *
 *      2-19 ： sub_module 演示
 *          location / {
 *              sub_filter '<a>immoc'  '<a>IMMOC' ;     替换html的内容
 *              sub_filter_once off；                   替换全文
 *          }
 *
 *
 *      2-20 ： nginx的请求限制
 *          连接频率的限制 : limit_conn_module
 *          请求频率的限制 : limit_req_module
 *          http1.0  tcp 不能复用
 *          http1.1  顺序性tcp复用
 *          http2.0  多路复用tcp
 *
 *          链接限制 :
 *          syntax ： limit_conn_zone key zone=name:size;
 *          default ： --
 *          context ； http
 *          作用: 申请空间的大小
 *
 *
 *          syntax : limit_conn zone number;
 *          default : --
 *          context : http ,server , location;
 *          作用 :  限制的并发
 *
 *      2.21 : 请求限制的配置语法
 *          syntax : limit_req_zone  key zone=name:size  rate = rate;
 *          default : --;
 *          context : http
 *        示例：
 *          http {
                limit_req_zone $binary_remote_addr zone=req_zone:1m rete=1r/s;
 *          }
 *
 *          syntax : limit_req zone=name  number nodelay;
 *          default : ---;
 *          context : http ,server, location;
 *        示例:
 *          location / {
                limit_req zone=reqzone brust=3 nodelay;
 *          }
 *
 *      
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 */