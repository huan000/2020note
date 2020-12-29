<?php
/**
 *  redis 特性：
 *      速度快  持久化       多种数据结构  支持多重语言   功能丰富
 *      简单    主从复制     高可用分布式
 *      速度快： 10w ops(每秒十万次读写)
 *               单线程   C语言编写
 *      持久化： 断电不丢失数据  rdb aof
 *      多种数据结构 ： 字符串 hash 列表 set zset  bitmaps
 *
 *   使用场景：
 *      缓存   计数器  消息队列   排行榜  社交网络  实时系统
 *
 *      redis 安装：  *************
 *
 *      相关文件：
 *          redis-server ： redis服务器
 *          redis-cli ： redis命令行客户端
 *          redis-benchmark ： 性能测试
 *          redis-check-aof ： 对aof 进行修复
 *          redis-check-dump : rdb 文件检查工具
 *          redis-sentinel : 高可用节点
 *
 *      三种启动方式：
 *          最简启动 ： redis-server
 *          配置文件启动 ： redis-server --port 6380
 *          动态参数启动 ： redis-server configPath
 *
 *      客户端的链接：
 *          redis-cli -h 100.00.00.00 -p 6379
 *
 *      redis 常用配置:
 *          daemonize : 是否以守护进程的方式启动redis  默认是no  建议yes
 *          port:  默认设置的端口 6379
 *          logfile： redis 系统日志
 *          dir: redis 工作目录
 */


/**
 *  第二章： 通用命令：
 *      keys * ：   遍历所有的key  (一般不在生产环境中使用)
 *      dbsize ：   查看有多少个key目前
 *      exists key ： 判断一个key是否存在   (存在返回1，不存在返回0)
 *      del key :  删除一个key  value (删除成功返回1，删除失败返回0)
 *      expire key seconds :  给key设置一个过期时间
 *      ttl key ： 查看一个key 的过期时间  (成功执行返回秒数， -2为key已经被删除了，-1
 *                                          key存在但是没有过期时间)
 *      persist key : 去掉key的过期时间
 *      type key ： 返回key的类型
 *
 *    单线程：
 *      所有命令在一瞬间只会执行一条命令
 *    单线程为什么这么快：
 *      1.纯内存  2.非阻塞io 3.避免线程切换
 *    注意的点：
 *      1.一次只运行一条命令  2.拒绝长命令  keys，flushall,flushdb,mutil/exec
 *
 *    字符串：
 *      get key ： 获取一个key的值
 *      set key ： 设置一个key的值
 *      del key ： 删除一个可以的值
 *      incr key :   自增一个key
 *      decr key :   自减一个key
 *      incrby key k ： key 自增k个
 *      decrby key k ： key 自减k个
 *
 *     实战： 缓存一个字符串
 *      1. 首先从redis中获取这个缓存
 *          $videoInfo = redis.get(key);
 *      2. 如果没有获取到这个缓存
 *          if($viednInfo == NULL){
        3. 那么从mysql中进行获取
 *              $videoInfo = mysql.get(key);
 *      4. 序列化放入缓存中
 *              redis.set(key,serialize($videoInfo));
 *          }
 *
 *
 *      set key value               不管key是否存在，都需要设置
 *      setnx key value             key不存在，才会进行设置   add操作
 *      set key value xx             key存在才可以进行设置    修改操作
 *
 *      mset key v1  key2  v2       设置多个k value
 *      mget k1 k2 k3               返回多个value
 *
 *      n次get和mget的区别 ：
 *      n次get ： n次网络时间+n次命令时间
 *      m次get ： m次命令时间
 *
 *
 *      getset key newvalue ： 设置新的value 并且返回旧的 value
 *      append key value ： 将value 追加到旧的value上
 *      strlen key ：  返回字符串的长度
 *
 *      incrbyfloat key 3.5 ： 增加key对应的值3.5
 *      getrange key start end ：  截取字符串
 *      setrange key index value ：  设置指定下标所有的值
 *
 *
 */

/**
 *  哈希：
 *      特点 ： hash vs string
 *      数据结构 ： key:1         field value   field2 v2
 *
 *      hget key field          获取hash key 对应的field的value
 *      hset key field value    设置hash key 对应的field的value
 *      hdel key field          删除hash key 对应的field的value
 *      hexists key field       判断hash key 是否有field
 *      hlen key                获取hash key field 的数量
 *      hmget key f1 f2         传入一批field的值
 *      hmset key f1 v1 f2 v2   传入多对f v
 *
 *
 *  实战：
 *      记录网站每个用户个人主页的访问量。
 *      hincrby user:1:info pageview count
 *
 *      hgetall key
 *      返回hash key 对应的所有的field 和 value
 *      hvals key
 *      返回hash key 对应的所有的field 的 value
 *      hkeys key
 *      返回hash key 对应的所有的field
 *
 *      小心使用hgetall ， 因为redis是单线程的，所以可能执行速度会比较慢
 *
 *      hsetnx key field value 设置hash key 对应的value，如果field已经存在，则失败
 *      hincrby key field intCounter   hash key对应的field的value自增intCounter
 *      hincrbyfloat key field floatCounter  浮点数版本
 *
 *   用户信息(string的实现)
 *        方式1：      user:1  json串
 *             优点： 编程简单，可能节约内存
 *             缺点： 序列化开销，设置属性要操作整个数据
 *        方式2：      user:1:name  value    这样做的好处就是可以单独
 *             优点： 直观，可以部分更新
 *             缺点： 内存占用较大 (上面只需要一个key，下面可能需要100个key)，key较为分散
 *        方式3：      进行hash 管理
 *             优点： 直观，节省空间，可以部分更新
 *             缺点： 变成稍微复杂，ttl不好控制 (2级无法设置过期时间)
 *
 *
 *    2-8： list(1)
 *      rpush key v1 v2 v3 : 从右边插入  结果就是 v1 v2 v3
 *      lpush key v1 v2 v3 ：从左边插入  结果就是 v3 v2 v1
 *      linsert  key before|after value newValue： 在list指定的前|后插入数据
 *      lpop key ： 从左边弹出一个元素
 *      rpop key ： 从元素右边弹出一个元素
 *      lrem key count value ： 根据count值，从列表中删除所有value相等的项
 *              count > 0 ,从左到右，删除最多count个value相等的项
 *              count < 0 ,从右到左，删除最多abs(count)个value相等的项
 *              count=0 删除所有value相等的项
 *
 *      ltrim key start end 按照索引修剪列表
 *      lrange key start end  获取指定索引范围所有item的值
 *      lindex key index    获取列表指定索引的item
 *      llen key            获取列表的长度
 *      lset key index newvalue   设置指定下标的新的值
 *      blpop 是lpop的阻塞版本 如果lpop会迅速返回值 blpop会阻塞等待有新的值进行插入
 *      brpop 同上
 *
 *      lpush + lpop  =  stack 栈     先进后出
 *      lpush + rpop  =  queue 队列   先进先出
 *
 *
 *  set集合：
 *      特点 ： 无序集合 无重复 集合间操作
 *      sadd key element ： 向集合中添加一个元素 如果element已经存在，那么添加失败
 *      srem key element ： 向集合中删除一个元素
 *      scard user:1:follow :  计算集合中的大小
 *      sismember user:1:follow it ： 判断it是否存在集合中 it=1 就是存在于集合中
 *      srandmember user:1:follow count : his 就是命中 然后随机挑选出了count个元素
 *      spop user:1:follow  : 从集合中随机弹出一个元素
 *      smembers ： 返回集合中的所有元素
 *
 *
 *      srandmember 和spop的区别： spop从集合中弹出一个元素 而且每回只能弹出一个
 *                      srandmember 不会破坏集合
 *      **** smembers 是一个大的命令 可能会阻塞redis ，size
 *
 *
 *      实战 ： 抽奖系统 spop 进行弹出中将用户
 *              tag标签  给用户添加标签， 给标签添加用户(sadd user:1  sadd tag1:
 *                      这两个东西其实是一个事务)
 *
 *
 *      集合间的：
 *      sdiff key1 key2  : 计算差集
 *      sinter key1 key2 ：计算交集
 *      sunion key1 key2 ：计算并集
 *
 *      共同关注的好友 可以用sinter 来实现
 *
 *
 * 有序集合：
 *      元素结构：
 *      key score ： value
 *      有序集合和无序集合：
 *      1. 都是无重复元素的
 *      2. 有序集合有分值 无序集合没有分值
 *
 *      列表和有序集合：
 *      1. 列表可以有重复元素  有序集合没有重复元素
 *      2. 都是有序的
 *
 *      重要api：
 *      zadd key score element(可以是多对的) ： 分数可以是重复的 但是element不能重复
 *      zrem key score element(可以是多对的) ： 删除一个有序集合的元素
 *      zincrby key incrscore element ： 增加或者减少元素的分数
 *      zcard key  :   返回一个有序集合中元素的个数
 *      zscore key element :  返回一个element 的 score
 *      zrank key element : 返回一个元素的排名，排名是从小到大进行排名
 *      zrange key 0 -1 withscore ： 返回一个排名列表
 *      zrangebyscore key minscore maxscore ： 返回指定分值区间的元素
 *      zcount key minscore maxscore ： 统计分值区间的元素的个数
 *      zremrangebyrank key start end : 删除指定排名内的升序元素
 *      zremrangebyscore key minscore maxscore ： 删除指定分数内的升序元素
 *
 *    实战：
 *      排行榜：
 *      score : timestamp(统计新上架产品) salecount  followcount
 *
 *      查缺补漏：
 *      zrank 是从低到高的排名  zrevrange 从高到低的排名
 *      zinterstore zunionstore 对两个有序集合进行交集和并集的计算
 *
 */

/**
 *  redis瑞士军刀：
 *      慢查询：
 *      redis命令的执行周期： 1.发送命令 2.排队 3.执行命令 4.返回结果
 *      1.慢查询是发生在第三阶段执行阶段的 而不是因为排队导致的
 *      2.客户端超时不一定是慢查询导致的
 *
 *      两个配置: showlog-max-len
 *      1. 先进先出的队列
 *      2. 固定长度的队列
 *      3. 队列保存在内存当中
 *
 *      slowlog-log-slower-than ：慢查询的阈值 当查询大于多少秒的时候记录到慢查询的队列
 *      如果设置为0 ： 那么所有的命令都会记录到慢查询日志
 *      如果设置为<0 : 那么所有的命令都不会记录到慢查询日志中
 *      默认值是10000微妙
 *
 *      showlog-max-len : 慢查询队列的长度  默认是128
 *
 *      配置方法：
 *      1. 修改配置文件后进行重启
 *      2. 动态配置 congfig set slowlog-max-len 1000
 *
 *      慢查询命令：
 *      slowlog get [n] : 获取慢查询队列
 *      slowlog len : 获取慢查询队列的长度
 *      slowlog reset ： 清空慢查询队列
 *
 *      运维经验：
 *      slowlog-max-len 不要设置的过大 默认是10ms 通常设置为1ms
 *      slowlog-log-slower-than  不要设置的过 通常1000左右
 *
 *
 *
 *  pipeline : 流水线
 *      1次时间 ： 1次网络时间 + 1次命令时间
 *      1次pipeline(n条命令) = 1次网络时间 + n次命令时间  ： 命令进行批量打包在服务器进行批量计算
 *      pipeline主要解决的是网络时间的问题
 *
 *
 *
 *  发布订阅：
 *      角色： 发布者(cli)，订阅者(cli)，频道(redis)
 *      命令：
 *              publish channel message ： 进行发布一个命令到一个频道
 *                                      (返回的结果是一个订阅人数)
 *              subscribe channel ： 返回的频道发布的信息
 *              unsubscribe channel :  取消订阅一个频道
 *              psubscribe pattern : 按照模式进行订阅
 *              pubsub channels ： 列出至少有一个订阅者的频道
 *              pubsub numsub channel ： 查看一个频道中有多少的订阅者
 *
 *      消息队列和发布订阅的区别：
 *              消息订阅的话 消息一旦发布了 那么所有的订阅者都会收到这个消息
 *              消息队列的话  只有抢到位置的人  可以消费这个队列
 *
 *
 *
 * bitmap:  ************待完成
 *
 */