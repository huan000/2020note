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
 *      2.10 nginx.conf 的基础配置语法
 *              
 *
 *
 *
 *
 *
 *
 *
 */