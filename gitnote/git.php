<?php

/**
 * 1. 集中式(svn): 代码存放在单一的服务器上面  便于项目的管理
 *          缺点 ： 1. 服务器宕机
 *                     员工写的代码得不到保障  无法恢复历史记录
 *                 2. 服务器故障
 *                     如果服务器爆炸 整个项目的历史记录丢失
 *
 *      数据保存 ： svn 每个版本保存的是增量更新  保存的是差异
 *                  就是补丁的模式 , 如果回滚的时候，那么要经历所有的补丁回滚
 *
 *
 * 2. 分布式(git): svn 的客户端只有最新版本控制系统的快照
 *                 git 把整个代码仓库完整的镜像下来 如果服务器宕机 事后都可以用
 *                      任何一个镜像出来的本地仓库恢复
 *
 *      数据保存 : 全量复制，每次都保存全部的更改和之前的数据
 *                存的东西很多。 但是git对每个版本做了极致的压缩 空间比svn多不了太多
 *                但是版本的回滚时间要快很多
 *
 *  查看git当前版本 : git --version
 *
 *  查看git的配置 ： git config --list
 *
 *  设置git客户端的用户名和邮箱  ：
 *          git config --global user.name "huan0000"
 *          git config --global user.email ""
 *
 *          --system 选项 ： 存放在/etc/gitconfig 文件， 系统中对所有用户都普遍适用的配置
 *          --global 选项 ： ~/.gitconfig 文件，用户目录下的配置文件只适用于该用户
 *          留空          ：  .git/config 文件， 当前项目的git目录中的配置文件 这里配置仅仅
 *                          针对当前有效
 *
 *          每一个级别都会覆盖上面一个级别的配置
 *
 *  3. ### git区域
 *      工作区  暂存区   版本库
 *
 *     ### git对象
 *      git对象  树对象  提交对象
 *
 *     初始化git仓库：
 *          git init : 找到当前仓库  然后执行命令
 *
 *      .git 目录中的文件 ：
 *          hooks : 钩子  在执行命令的时候进行钩子操作
 *          info  : 排除文件的信息
 *          objects ： 存储所有的数据内容  相当于数据库
 *          logs  ： 保存日志信息
 *          refs  ： 存放分支有关的信息
 *          config ： 目录的配置文件信息
 *          description : 用于显示仓库的描述信息的
 *          HEAD : 文件指示目前被检出的分支
 *          index : 暂存区的信息的内容
 *
 *  4. git对象 :
 *      git 的核心部分是一个简单的键值对数据库 ，可以向这个数据库中插入任意类型的内容，
 *      它会返回一个键值，通过该键值可以在任意时刻再次检索该内容
 *      echo "test content" | git hash-object -w --stdin
 *      解析 :
 *          -w  如果加了-w就会往数据库里面区存， 如果不加这个参数就会返回对应存储内容的hash
 *
 *      根据hash 拉取相应的内容
 *          git cat-file -p  HASH  ： 查看存储的是什么内容
 *          git cat-file -t  HASH  :  查看存储的键值对是什么类型
 *                                      (键值对在git内部是一个blob类型)
 *
 *      将文件保存到git管理
 *          git hash-object -w  ./filepath
 *
 *      在以上操作中，文件名并没有被保存，我们仅仅保存了文件的内容
 *      当前的操作都是在对本地的数据库进行操作，不涉及暂存区
 *
 *
 *  5. 树对象
 *      树对象，它能解决文件名保存的问题，也允许我们将多个文件组织到一起，git以
 *      一种类似unix文件系统的方式存储内容。其中树对象就对应了目录，git对象就包含了
 *      文件的内容。 一个树对象也可以包含另一个树对象
 *
 *      构建树对象 ：
 *      利用 update-index命令 为test.txt 文件的首个版本 -- 创建一个暂存区 ，
 *      并通过write-tree 命令生成树对象
 *      命令 ：
 *          。。。。。。。。
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

