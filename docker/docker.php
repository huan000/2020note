<?php
/**
 *  安装docker : 看官网
 *
 *  2-7 ： docker-machine
 *      docker-machine version    查看docker machine的版本
 *      docker-machine create demo     创建一个 虚拟机
 *      docker-machine ls         列出所有的机器
 *      docker-machine ssh demo   进入到这个虚拟机中
 *      docker-machine stop       停掉某个docker机器
 *      docker-machine env demo   查看某台docker的环境变量
 *      eval $(docker-machine env demo)    直接运行上面的虚拟机
 *
 *
 *  3-1 ： docker的架构和底层技术
 *      docker Engine ：
 *          后台进程 dockerd
 *          rest api server
 *          cli接口( docker )
 *
 *      四大块 :
 *          network  data volumes  container  image
 *
 *      底层的技术支持 :
 *          Namespaces : 做隔离pid,net,ipc,mnt,uts
 *          Control groups : 做资源限制
 *          Union file systmes ：Container 和 image的分层
 *
 *  3-2 ： docker image(镜像) 概述
 *      什么是image :
 *      1.文件和meta data的集合
 *      2.分层的， 并且每一层都可以添加改变删除文件，称为一个新的image
 *      3.不同的image可以共享相同的layer
 *      4.image本身是read-only的
 *
 *      底层 (bootfs)内核  -> Base image(rootfs) 系统
 *
 *      image4 和 image2 可能是共享了同一个base image。 image4 是2 的迭代
 *
 *      docker image ls  :            查看本地已经有的image
 *      简写 : docker images
 *
 *      docker image rm  ： 删除一个image
 *      简写 ： docker rmi
 *
 *      image 的获取 ：
 *      1. 通过 dockerfile 进行获取
 *      2. 从 registry 进行拉取
 *          docker hub地址 : https://hub.docker.com
 *          例子 : docker pull ubuntu:14.04
 *
 *  3-3 : 创建一个自己的base-image
 *      创建dockerfile:
 *      vim Dockerfile
 *      FROM scratch
 *      ADD hello /
 *      CMD ["/hello"]
 *
 *      docker 加载 Dockerfile
 *      docker build -t leo/hello-world .
 *
 *      查看dockerimage的分层关系 ：
 *      docker history [imageID]
 *
 *      运行image :
 *      docker run leo/hello
 *
 *
 *  3-4: 初识container ：
 *      image 本身是只读的 container 是可读可写的
 *      container是什么:
 *      1. 通过image 创建
 *      2. 在image layer 之上建立一个container layer(可读写)
 *      3. 类比面向对象 : 类和实例
 *      4. image 负责app的存储和分发，container负责运行app
 *
 *      查看本地正在运行的container
 *      docker container ls
 *      docker container ls -a   查看运行过的所有docker命令
 *      简写 : docker ps -a
 *
 *      运行一个container容器
 *      docker run containername
 *      动态的运行一个容器
 *      docker run -it containername
 *
 *      删除指定的container
 *      docker container rm  ID
 *      简写: docker rm
 *
 *      查看所有container的id
 *      docker container ls -a
 *
 *      删除所有的container
 *      docker rm $(docker container ls -aq)
 *
 *      查找所有已经退出的容器
 *      docker rm $(docker container ls -f "status=exited" -q)
 *
 *
 *  3-5 : 构建自己的docker镜像
 *   方法1
 *      将已经有过变化的container变成新的image
 *      docker container commit [REPOSITORY:[TAG]]
 *      简写 ： docker commit
 *      作用: 这样会生成一个基于老版本的新的image
 *
 *   方法2：
 *      利用dockerfile
 *      FROM centos
 *      RUN yum install -y vim
 *
 *      生成image: docker build -t leo/centos-new .
 *
 *  3-6 ： dockerfile 语法梳理及最佳实践
 *      FROM :
 *        FROM scratch   制作base image  从头开始制作baseimage
 *        FROM contos    从centos的最后一个版本进行制作
 *        FROM ubuntu:14.04     从指定的版本进行制作baseimage
 *
 *      LABEL :
 *        LABEL maintainer="xxx@xx.com"     指定作者
 *        LABEL version="1.0"     指定版本
 *        LABEL description="this is description"       指定描述信息
 *
 *      RUN :
 *        RUN yum update && yum install -y vim \ python-dev
 *
 *      WORKDIR :
 *        WORKDIR /test        如果没有会自动切换目录
 *        WORKDRI demo
 *        RUN pwd              执行命令 /test/demo
 *
 *      ADD and COPY :
 *        ADD hello /          add可以解压缩 copy不行
 *
 *      ENV 设置常量
 *
 *  3-7 ： RUN vs CMD vs ENTRYPOINT
 *      RUN 执行命令并创建新的Image Layer
 *      CMD 设置容器启动后默认执行的命令和参数
 *      ENTRYPOINT 设置容器启动时运行的命令
 *
 *      CMD:
 *        容器启动时默认执行的命令
 *        如果docker run指定了其它命令 CMD命令被忽略
 *          例子： Docker run -it [image] /bin/bash 就不会输出，因为指定了其他的命令
 *        如果定义了多个CMD 只有最后一个会执行
 *
 *      ENTRYPOINT:
 *        让容器以应用程序或者服务的形式运行
 *        不会被忽略 一定会被执行
 *
 *  3-8 ：镜像的发布
 *      1. 登陆dockerhub
 *          docker login
 *      2. 上传
 *          docker push leo/test:1.0
 *      3. 下载
 *          docker pull leo/test:1.0
 *
 *     搭建一个私有的dockerhub 仓库
 *      docker run -d -p 5000:5000 --restart always --name registry registry:2
 *      上传 ：
 *      重新构建一个带有远程名称的image
 *      docker build -t xxx.xxx.xxx.xx:5000/hello-world .
 *      修改配置文件：
 *      vim /etc/docker/daemon.json
 *      {
            "insecure-registries":["xxx.xxx.xx.xxx:5000"]  添加信任远程地址
 *      }
 *
 *      vim /lib/systemd/system/docker.service
 *      [Service]
 *      EnvironmentFile=-/etc/docker/daemon.json        加载配置文件
 *
 *      重启docker:
 *      service docker restart
 *
 *      docker push  xxx.xxx.xxx.xx:5000/hello-world
 *
 *      docker registry api:
 *      查看所有的image ：
 *      xxx.xxx.xxx.xx:5000/v2/_catalog
 *
 *
 *   3-9 : dockerfile 实践2
 *      创建一个docker程序的baseimage
 *      FROM python:2.7
 *      LABEL "maintainer=leo leo<275916417@qq.com>"
 *      RUN pip install flask
 *      COPY app.py /app/app.py
 *      WORKDIR /app
 *      EXPOSE 5000
 *      CMD ["python","app.py"]
 *
 *      在后台启动一个container
 *      docker run -d leo/test
 *
 *   3-10 ：container 的操作
 *      进入到一个后台运行中的容器当中
 *      docker exec -it [DOCKERID] /bin/bash
 *
 *      停止正在运行的container
 *      docker stop [DOCKERID]
 *
 *      开启已经停止的container
 *      docker start | restart [DOCKERID | DOCKERNAME]
 *
 *      清除掉已经停止的container
 *      docker rm $(docker ps -qa)
 *
 *      后台启动一个有名字的container
 *      docker run -d --name=demo leo/test
 *
 *      查看container的详细信息
 *      docker inspect [DOCKERID]
 *
 *
 *   3-11 : docker file 实践2
 *      FROM ubuntu
 *      RUN apt-get update && apt-get install -y stress
 *      ENTYRPOINT ["/usr/bin/stress"]
 *      CMD []                                  //启动docker container的时候接收的参数
 *
 *
 *
 *   4-1 ： 本章概述和实验环境介绍
 *      DOCKER NETWORK
 *
 *
 *   4-2 :  网络的基础问题
 *      iso 七层 : 物理 ， 数据链路层 ， 网路层 ， 运输层 ， 会话层 ，表示层 ，应用层
 *      tcp/ip 五层 ： 物理层 ， 数据链路层  ， 网络ip层 ， 运输层 (tcp/udp) , 应用层
 *
 *     ping 和 telnet
 *      ping : 验证IP地址的可达性
 *      telent : 验证服务地址的可达性
 *
 *   4-3 ： linux 网络空间命名
 *      ip netns list               网络空间列表
 *      ip netns delete test        删除一个网络空间
 *      ip netns add test           创建一个网络空间
 *      ip netns exec test ip a     查看网络空间的地址
 *
 *      ******后续部分没有听懂
 *
 *   4-4 ： docker bridge0 详解
 *      本机上的多个container是可以互相通过本机的docker0 进行通信的
 *      如果访问外网的话 通过docker0 进行nat 网络地址转换成 eth0 的地址
 *      作为linux 主机的数据包 就可以访问外网了
 *
 *   4-5 :  容器之间的link
 *      两个容器之间根据name进行绑定
 *      docker run -d -t --name test2 --link test1 centos1 /bin/bash
 *      note: link是有方向的，是从test2 link 到 test1 ,反过来不行
 *      但是如果两个容器都链接到用户自己创建的bridge上面，那么两个容器默认是创建好了的
 *
 *      docker network ls ： 默认链接的是bridge网络链接
 *
 *      创建一个网络链接 :
 *      docker network create -d bridge my-bridge
 *
 *      启动时选择网络链接 :
 *      --network my-bridge
 *
 *      后期选择网络链接 :
 *      docker network connect my-bridge test1
 *
 *
 *   4-6 :  容器的端口映射
 *      查看bridge的网络地址：
 *      docker network inspect bridge
 *
 *      进行端口映射:
 *      docker run --name web -p 80:80 nginx
 *      将container的80端口映射为本地主机的80端口
 *
 *   4-7 :  容器网络 none 和  host
 *      docker run -d -t --name test1 --network none contos /bin/bash
 *      none : 是一个孤立的网络环境，用于存储密码之类的信息 外部是无法访问的
 *      host : 和主机共享一个网络空间，但是会带来端口冲突之类的问题
 *
 *   4-8 ： 多容器复杂应用的部署
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


