<?php
/**
 * 第五章: redis 持久化和运维的选择
 *      持久化的方式：
 *      1.快照：redis rdb   mysql dump
 *      2.日志：redis aof   mysql binlog
 *
 *   RDB:
 *      触发机制：
 *      save(同步) ：如果存在老的rdb文件，因为是同步的，会进行阻塞，会进行新老替换
 *              优点是不会消耗额外的内存，但是会阻塞客户端
 *      bgsave(异步命令) : 会单独fork出来一个子进程进行生成
 *              优点是不会阻塞客户端的命令，但是会消耗额外的内存
 *      自动(达到触发机制的时候自动的生成日志) 内部执行了bgsave:
 *              save 900 1    (一般不进行自动生成)
 *              dbfilename dump.rdp            最佳配置 dump{port}.rdb
 *              dir ./                   rdb生成的位置
 *              stop-writes-on-bgsave-error yes        如果生成过程出现了错误是否停止写入
 *              rdbcompression yes          是否进行压缩
 *              rdbchecksum  yes            是否进行检验
 *
 *      1. 全量复制
 *      2. debug reload
 *      3. shutdown
 *
 *      RDB的问题：
 *      1. 耗时 2.不可控 容易丢失数据
 *
 *
 *   AOF：
 *      原理： 客户端每写一条命令，就在日志中纪录一条命令
 *
 *      aof三种策略：
 *              always ： 写每一条命令都会同步到硬盘当中
 *                      优点： 不会丢失数据
 *                      缺点： io开销非常的大
 *              everysec(通常使用) : 写命令都会记录到缓冲区中，每秒都会把数据刷入到硬盘当中
 *                      优点： 没有那么大的资源消耗
 *                      缺点： 有可能会丢失1秒的数据
 *              no : 操作系统决定什么时候将缓冲区的内容刷入就刷入
 *                      优点： 不用管
 *                      缺点： 不可控
 *
 *      aof重写：
 *              会把aof文件里面的重复的或者无效过期的数据都删除
 *
 *         重写的两种实现方式：
 *              1. bgrewriteaof : fork一个子进程，异步的进行重写
 *
 *              auto-aof-rewrite-min-size : aof文件重写需要的尺寸
 *              auto-aof-rewrite-percentage : aof文件的增长率
 *
 *              appendonly yes           开启aof功能
 *              appendonlyfilename “”    aof文件的名字
 *              appendfsync everysec     aof三种同步的策略
 *              no-appendfsync-on-rewrite  yes    aof重写的时候是否进行append操作
 *
 *
 *         命令：
 *              aof_current_size  aof当前的尺寸
 *              aof_base_size     aof上次启动和重写的尺寸
 *
 *      RDB和AOF 的对比：
 *              1.启动优先级，如果都启动的话 aof的优先级要高于rdb
 *              2.体积  aof的体积更大
 *              3.恢复速度 aof速度慢
 *              4.数据安全性： rdb丢数据  aof根据策略决定
 *              5.rdb操作重(全盘写入)  aof操作轻(追加日志)
 *
 *      rdb最佳策略：
 *              RDB 不建议使用
 *              aof 建议开 如果redis只做缓存层可以关闭aof 因为数据库已经持久化了
 *                  建议每秒刷盘
 *
 *
 *      持久化的常见问题：
 *              1. fork操作： 执行bgsave bgrewriteaof 的时候首先都会执行一个fork操作
 *                 本身fork操作是同步的操作
 *                 改善：
 *                 1.优先使用物理机或者高效支持fork操作的虚拟化技术
 *                 2.控制redis实例化最大可用内存：maxmemory
 *                 3.合理配置linux内存分配策略：vm.overcommit_memory=1
 *                 4.降低fork频率：例如放宽aof重写自动触发机制，不必要的全量复制
 *
 *              子进程的开销和优化：
 *              1.cpu：开销 rdb和aof文件生成，属于cpu密集型
 *                    优化： 不做cpu绑定，不和cpu密集型部署
 *
 */

/**
 *  REDIS 主从复制：
 *     什么是主从复制：
 *        一个master 可以有一个slave或者多个slave节点。
 *        主节点通常用来写操作  从节点通常用来读操作
 *        数据流向是单向的 必须由master 流向 slave
 *     作用：
 *        1.提供了数据副本  2.拓展读性能
 *     单机的问题：
 *        1.机器故障  2.容量瓶颈  3.qps瓶颈
 *
 *     配置：
 *      两种方式：
 *      1.命令 ：
 *              slaveof 127.0.0.1 6379:在slave节点上 执行  ,这个过程是异步的
 *              slaveof no one : 取消主从复制， 这个过程是异步的 ，但是不会
 *                      清除已经复制的数据，在一次复制别的主节点的时候才会清除原有数据
 *              优点： 无需重启， 缺点：不便于管理
 *      2.配置：
 *              slaveof IP PORT
 *              slave-read-only yes      如果想进行从节点只读的话，添加配置
 *              优点： 统一配置  q
 *
 *   全量复制和部分复制：
 *      全量复制： 在第一次不知道主节点的runid和偏移量的时候是需要做全量复制的
 *      部分复制： 如果已经产生了链接，那么会进行部分复制
 *
 *   全量复制的开销： bgsave的时间 ，rdb文件的网络时间 ，从节点清空数据的时间
 *              从节点加载rdb的时间， 可能的aof重写的时间
 *
 *   故障处理：
 *      slave故障：
 *          多台slave的情况下， 把产生故障的机器转移到没有产生故障的机器的节点上
 *      master故障：
 *          再找一个slave成为master
 *          1. 找一个slave 执行slaveof no one 成为新的master
 *          2. 再找一个slave  执行 slaveof newmaster 去成为新机器的slave
 *
 *      以上的操作没有真的执行故障的自动转移
 *
 *   主从复制常见的问题：
 *      1.读写分离
 *      读流量分摊到从节点上
 *      问题：
 *      复制数据的延迟
 *      读到过期的数据 3.2的时候已经解决了这个问题
 *      从节点故障
 *
 *      2.规避全量复制
 *         产生条件：
 *           1．第一次全量复制　 第一次无法避免
 *           可以在低峰处理
 *           2. 节点运行的run id 不匹配
 *           主节点重启(运行id变化)
 *      3.规避复制风暴
 *           一个master节点挂掉了很多slave节点 那么所有的节点都会进行主从复制
 *
 *      案列：
 *      1. 主从节点的maxmemory不一致： 丢失数据
 *
 */

/**
 *  第八章： sentinel 高可用
 *      redis sentinel: 安装
 *      1. 配置开启主从节点
 *      2. 配置开启sentinel监控主节点 (sentinel是特殊的redis)
 *      sentinel 只需要监控主节点，它会自动通过 info 知道从节点的信息而进行监控
 *
 *     配置sentinel节点：
 *      vim sentinel.conf
 *       sentinel 主要配置：
 *      port ${port}  :  配置sentinel的端口
 *      dir ""        :  配置工作目录
 *      logfile ""    :  配置日志目录
 *      sentinel monitor mymaster 127.0.0.1 7000 2         :  最后一个参数是几个sentinel
 *                                                      发现节点有问题了进行故障转移
 *      sentinel down-after-milliseconds mymaster 30000    :  ping 超过30000秒如果不通那么认为有问题
 *      sentinel parallel-syncs mymaster 1                 :  复制是并发的还是串行的 1代表每次只复制一个
 *      sentinel failover-timeout mymaster 180000          :  故障转移时间
 *
 *      启动sentinel节点：
 *      redis-sentiinel  redis-sentinel-26379.conf
 *
 *
 *      三个定时任务：
 *      1.每10秒每个sentinel对master和slave执行info操作
 *           发现slave节点   确认主从关系
 *      2.每2秒每个sentinel通过master节点的channel交换信息(pub/sub)
 *           通过__sentinel__:hello 频道交互
 *      3.每1秒每个sentinel对其他sentinel和redis执行ping的操作
 *           心跳检测的过程，每个sentinel对相同的sentinel和其他master节点执行ping的操作
 *
 *      主观下线和客观下线：
 *      主观下线就是只单个sentinel节点对某个节点ping后无效
 *      客观下线就是多个sentine节点如果超过了选举数的节点都判断无效，那么就是客观下线了
 *
 *      领导者选举：
 *      原因： 只需要一个sentinel节点完成故障转移，所以需要进行领导者选举
 *      选举： 通过sentinel is-master-down-by-addr 命令都希望成为领导者
 *          1.每个做主观下线的sentinel节点向其他sentinel发送命令，要求将它设置为领导者
 *          2.收到命令的sentinel节点如果没有同意通过其他sentinel节点发送命令，那么
 *              那么将同意该请求，否则会拒绝
 *          3.如果该sentinel节点发现自己的票数已经超过sentinel集合半数且超过quorun，
 *              那么它将成为领导者
 *
 *      故障转移：
 *      1.从slave节点中选出一个合适的节点作为新的master节点
 *      2.对上面的slave节点执行slaveof no one命令让其成为master节点
 *      3.向剩余的slave节点发送命令，让他们成为新的master节点的slave节点，复制规则
 *          和parallel-syncs
 *      4.更新对原来master节点配置为slave，并保持着对其"关注"，当其恢复后命令
 *        它去复制新的master节点
 *
 *
 *      运维中常见的问题：
 *      节点运维   高可用的读写分离
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
