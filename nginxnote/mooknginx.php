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
 *        burst=3 ； 保证下一秒肯定有三个请求
 *
 *      
 *      2.23 - 30: nginx的访问控制
 *        基于ip的访问控制 http_access_module
 *          syntax ： allow address | CIDR | UNIX | ALL;
 *          default ：——————
 *          context : http ,  server , location , limit_except
 *
 *          syntax : deny address | CIDR | UNIX | ALL;
 *          default ： ---
 *          context : http , server ,location , limit_except
 *
 *          例子 : location / {
                allow 192.168.31.142;
 *              deny all;
 *          }
 *          上面也可以反过来
 *          功能限制 : 这个是通过 remote_addr 进行访问控制限制的
 *          如果中间有代理服务器的话  那么最终的server 的 remote_addr 显示的是 代理服务器的ip
 *          而http_x_forwarded_for = clientip,proxy1,proxy2;
 *
 *          解决局限性的方法 :
 *          1. 采用 http_x_forward_for
 *          2. 结合geo模块
 *          3. 通过http自定义变量传递
 *
 *        基于用户的信任登陆    http_auth_basic_module
 *          syntax: auth_basic string | off;
 *          default： auth_basic off;
 *          context: http,server,location,limit_except
 *          作用 输入密码的提示信息
 *
 *          syntax: auth_basic_user_file file;
 *          default: --- ;
 *          context: http,server,location,limit_except;
 *          作用 存用户名和密码信息的文件
 *
 *          示例 ：
 *          location / {
                auth_basic "interpassward";
 *              auth_basic_user_file ./auth_conf;
 *          }
 *
 *          密码文件的格式:
 *          name1:pass1:comment
 *          name2:pass2
 *
 *          生成密码文件：
 *          安装 yum httpd-tools
 *          htpasswd -c ./auth_conf  leo
 *
 *      局限性 : 1. 用户信息依赖文件的方式
 *               2. 操作管理机械 效率低下
 *
 *
 *  3.1-5 ：
 *      静态资源服务场景 : cdn
 *      例如服务器在新疆，将内容分发到北京的服务器上，那么北京的用户就可以快速的请求到
 *      资源
 *
 *      相关拓展模块
 *      syntax : sendfile  on | off;
 *      default : sendfile off;
 *      context : http , server , location , if in location
 *
 *      tcp_nopush :
 *      syntax : tcp_nopush  on | off;
 *      default : tcp_nopush off;
 *      context : http , server ,location;
 *      作用： sendfile 开启的情况下 ，提高网络包的传输效率
 *      多个tcp包可以合并发送 节省链接的资源
 *
 *      tcp_nodelay
 *      syntax : tcp_nodelay  on | off;
 *      default : tcp_nodeay on;
 *      context : http , server ,location;
 *      作用 ： keepalive 链接下 ， 提高网络包的传输实时性 有tcp包就进行发送  于上面
 *      形成对应
 *
 *      压缩 ：
 *      syntax : gzip  on|off;
 *      default : gzip off;
 *      context :  http , server , location
 *      作用 : 服务端进行压缩  客户端进行解压缩进行读取
 *
 *
 *      syntax : gzip_comp_level  level;
 *      default :  gzip_comp_level 1;
 *      context :  http , server , location
 *      作用 ：控制压缩比
 *
 *      syntax ： gzip_http_version 1.0 | 1.1;
 *      default : gzip_http_version 1.1;
 *      context :  http , server , location
 *
 *
 *      预读gzip功能的相关模块 : http_gzip_static_module  预读gzip功能
 *      支持gunzip的压缩方式(部分浏览器不支持gzip压缩):http_gunzip_module
 *
 *      例子 :
 *      location / {
            gzip on;
 *          gzip_http_version 1.1;
 *          gzip_comp_level 1;
 *          gzip_types text/plain  ... ;
 *      }
 *
 *
 *     3.6 浏览器的缓存机制
 *      http协议定义的缓存机制 如: expires , cache-control 等等
 *      访问流程 :
 *      无缓存情况:
 *      浏览器请求 -> 无缓存 -> 请求web服务器 -> 请求响应，协商 -> 呈现
 *      有缓存情况:
 *      浏览器请求 -> 有缓存 -> 效验过期 ->
 *      如果过期:
 *      请求etag 如果有向web服务器请求带if-none-match 或者 304 到客户端
 *      如果etag 没有请求last-modified 向服务器请求中带if-modified-since 返回304 到客户端
 *      如果都没有 重新请求数据返回200 到客户端
 *
 *      如果没过期:
 *      直接从缓存中读取
 *
 *
 *
 *      如何效验过期:
 *      是否过期: expires 1.0  ,  cache-control(max-age) 1.1
 *         作用 : 通过cache-control 后面的时间信息判断本地缓存是否过期 如果过期了重新访问服务器
 *      协议中etag头信息校验: etag
 *         作用 : 如果本地缓存过期了 判断服务器的文件是否有更改 通过校验码
 *          因为last-modified 只能够精确到秒 所有etag的出现可以进行是否修改了文件的识别
 *      last-modified头信息校验: Last-Modified
 *         作用 : 如果本地缓存的文件过期了 判断服务器的文件的时间是否有更改
 *
 *      nginx 中配置缓存的响应头
 *      添加cache-control ，expire头
 *      syntax : expires [modified] time;
 *      default : expires off;
 *      context : http , server ,location
 *
 *      例子 :
 *      location \ {
            expires  24h;
 *          root /opt/app/code;
 *      }
 *      作用: 如果加上就会在响应头中返回 etag 和 last-modified
 *
 *      在有缓存的时候 ： 浏览器会通过自身行为在请求头中添加Cache-Control：max-age=0
 *      这样在每回请求的时候都会去校验etag 和 last-modified
 *      但是如果加了 expires 24h； 响应头中会有 Cache-Control:max-age=86400
 *
 *      3.8 跨域访问
 *      syntax: add_header name value [always];
 *      default : ---;
 *      context: http ,server, location;
 *
 *      Access-Control-Allow-Origin
 *
 *
 *      3.9： 跨站访问场景配置
 *      例子 ：
 *      location / {
            add_header Access-Control-Allow-Origin http://www.jesonc.com;
 *          add_header Access-Control-Allow-Methods GET,POST,PUT,DELETE,OPTIONS;
 *          root /html;
 *      }
 *
 *      2.10.11 : 防盗链
 *      目的 : 防止网站资源被盗用
 *      防盗链的设置思路 :
 *      首要方式： 区别哪些请求是非正常的用户请求
 *      基于http_refer 防盗链配置模块: http_refer 代表上一个页面的地址信息
 *
 *      例子 :
 *      location / {
            valid_referers none blocked 116.12.132.21;
 *          if($invalid_referer){       // 如果上面的条件不满足 则invalid_referer会为1
                return 403;
 *          }
 *          root /html;
 *      }
 *      note : none 允许没有referer信息的过来
 *             blocked  允许不是http:// 这种标准形式过来的请求
 *             只允许 特定ip过来进行访问
 *
 *      3.12 ： nginx作为代理服务器
 *      代理模式 : 客户端 -> 代理服务器 -> 服务端
 *      nginx 可以实现 http,icmp,pop,imap,https,trmp 等请求协议的代理
 *
 *      正向代理 和 反向代理
 *      区别 : 代理的对象不一样 ， 正向代理的对象是客户端 (代理服务器当作一个客户端继续请求)
 *                                反向代理的对象是服务端  (代理服务器是服务端)
 *
 *      3.13 ： 代理服务器的配置语法
 *      syntax ： proxy_pass URL;
 *      default : --;
 *      context : location
 *      作用: 当请求到这台nginx服务器以后，代理服务器继续请求的服务器地址
 *
 *      反向代理配置示例:
 *      location / {
            proxy_pass http://xxx.xxx.xxx.xx:80;
 *      }
 *      作用: 将请求转发到某台服务器上
 *
 *      正向代理的配置 :
 *      服务器 :
 *      location / {
            if ( $http_x_forwarded_for !~* "^116\.62\.103\.228"){
 *              return 403;
 *          }
 *          root /html;
 *      }
 *
 *      客户端服务器:
 *      location / {
 *          resolver 8.8.8.8;
            proxy_pass http://$http_host$request_uri;
 *
 *      }
 *
 *      3.16 ：代理语法补充
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