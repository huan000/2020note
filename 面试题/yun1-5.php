<?php
/**
 *      权力和义务的形态：
 *      应有权力和义务， 习惯， 法定， 现实
 *
 *  第一章：
 *    1.用php 打印出当前时间：
 *      2012-5-12 22：22：22
 *      date('Y-n-d H:i:s');
 *      2012-5-2 22：22：22
 *      date('Y-n-g H:i:s');
 *
 *
 *    改成中华人民共和国的时区：
 *      date_default_timezone_set("PRC");
 *      date.timezone = PRC    这个是php.ini 中进行设置
 *
 *     2.字符串转数组： explode("",$str);
 *      数组转字符串:   implode(',',$str);
 *      字符串截取： substr($str,1,10);
 *      字符串替换： str_replace($str,"a","b");  preg_replace(REX,"",$str);
 *      字符串查找： preg_match();  preg_match_all(REX,$str,$ms);
 *
 *     3. 查找文件名和目录地址
 *      basename();   dirname();
 *
 *     4. 将 $date='08/26/2003';  转换成 2003/08/26
 *      echo preg_replace('/(\d+)\/(\d+)\/(\d+)/','$3/$1/$2',$date);
 *
 *
 *     5. 从表login中选出name字段包含admin的前10条结果所有信息的sql语句
 *      select * from login where name like '%admin%' limit 10 order by id;
 *
 *        增加索引的sql语句
 *      alter table user add index in_username(username);
 *        删除一个索引
 *      alter table t1 drop indexname;
 *        增加一个主键
 *      alter table t1 add primary key(id);
 *
 *     6. 无限分类的实现原理
 *     递归：
 *          function getTree($arr,$pid=0,$level=0){
                static $list = array();
 *              foreach($arr as $key =>$val){
                    if($val['pid'] = $pid){         //如果是主分类
 *                      $val['level'] = $level;
 *                      $list[] = $val;
 *                      unset($arr[$key]);
 *                      getTree($arr,$val['id'],$level+1);        //递归调用
 *                  }
 *              }
 *              return $list;
 *          }
 *
 *      引用：
 *          function getTree($data){
                $items = array();
 *              foreach($data as $key=>$val){
                    $items[$val['id']] = $val;          //主键为key的新的数组
 *              }
 *              $tree = array();
 *              foreach($items as $k=>$item){           //循环这个新的数组
 *                  if(isset($items[$item['pid']])){   //新的数组如果key[0] 那么就是父分类
 *                      items[$item['pid']]['son'][] = &$items[$k];
 *                  }else{
 *                      $tree[] = &$items[$k];
 *                  }
 *              }
 *              return $tree;
 *          }
 *
 *
 *      7. 写一个函数 尽可能高效的 从一个标准url里面取出文件的拓展名
 *          $url = http://www.test.com/abc/de/fg.php?id=1
 *          方法1：
 *              parse_url($url);
 *              结果 ：
 *              array(
                    scheme => http,
 *                  host => www.test.com,
 *                  path => /a/b/index.php
 *                  query => id=1
 *              );
 *
 *              pathinfo($arr[path]);
 *              结果 :
 *              array(
                    dirname => /a/b
 *                  basename => index.php
 *                  extension => php
 *                  filename => index
 *              );
 *
 */

/**
 *  第二章 ：
 *      描述一下大流量高并发网站的解决方案
 *      1. lvs或者nginx 负载均衡器
 *      2. 缓存 nginx web cache 缓存
 *      3. web 服务器选型
 *      4. php 代码静态化
 *      5. memcache redis
 *      6. mysql 的优化
 *      7. 主从复制
 *      8. 数据库分表或者分区
 *      9. 磁盘分布
 */





/**
 *  3. 第三讲
 *     如何防止sql注入：
 *      表单尽量用post提交，表单判断控制走get， 因为get比post速度快
 *      $_SERVER[HTTP_REFERER]  判断提交者的源头
 *      开启 addslashes 在 ' " \ 头加上\
 *      密码一定要进行加密
 *      服务器本身的安全
 *
 *     
 *
 *
 *     如何防止盗链 :
 *      基于服务器防止盗链 ：
 *          apache 和 nginx 做 rewrite 基于源来做判断防止盗链
 *      代码防止 ：
 *          $_SERVER['HTTP_REFERER'] 判断访问来源
 *
 *
 *     用php写出一个安全的登陆系统需要注意哪些方面
 *      1.服务器开启ssl 安全套接字 服务器证书
 *      2.用户注册的时候尽量使用php进行验证
 *      3.用户登陆的时候尽量使用图片加验证码的形式
 *      4.以post提交给后端的php程序
 *
 *
 *
 *
 */


/**
 *  第四讲：
 *     1. 输出用户代理的语句是什么
 *       $_SERVER['HTTP_USER_AGENT'];
 *
 *     2. PHP 有几种标量类型
 *       四种 ： 布尔型(bool) , 整型 integer ，浮点型 float ，字符串 string
 *
 *        PHP 中的伪类型有几种
 *       混合型 (mixed) , 数字型 (number) , 回调 (callback)
 *
 *        PHP 中的复合类型有几种
 *       数组和对象
 *
 *        浮点型(float) 和 双精度 (double) 是同一种类型
 *
 *
 *      3. echo  function_exists('print');
 *        输出的结果是空   ；  因为print 是语言结构
 *
 *        结果是false  ， echo false  , 那么显示的就是空
 *        如果结果是 true ， echo true ， 那么显示的就是1
 *
 *       语言结构： echo , print , list , for , foreach ,pop ,push ,shift ,unshift ,eval
 *        echo print 都是语言结构   print_r 是函数
 *
 *      4. 下面不是语言结构的一项是
 *          a. array
 *          b. eval
 *          c. each
 *          d. list
 *
 *          正确答案 : c
 *
 *      5. 执行下面代码的结果是什么
 *          $bool = TRUE ;
 *          echo gettype($bool);   boolean
 *          echo is_string($bool);      空
 *
 *      6. 写出下面代码的执行结果
 *          $a = 12;
 *          $b = 012;
 *          $c = 0x12;
 *          echo $a;  12
 *          echo $b;  10
 *          echo $c;  18
 *
 *      7.  下面的代码执行的结果是什么
 *          echo 1+2+"3+4+5";   6  ;  3+3
 *
 *      8.  下面的代码加入下面哪个函数后返回true
 *          return ? == 'A';
 *           a. ord(65);
 *           b. chr(65);
 *           c. 65+'';
 *           d. ""+65;
 *
 *              chr 数字转字母
 *              ord 字符转数字
 *          答案是 b
 *
 *
 *      9.  一下代码输出正确的是什么
 *           $a = array(1=>5,5=>8,22,2=>'8',81);
 *           echo $a[7]  81
 *           echo $a[6]  22
 *           echo $a[3]  空
 *
 *          如果不知道下标是几， 那么肯定是前一个的最大下标加1
 *
 *      10. 下面代码的输出结果是什么
 *           $a[bar] = 'hello';
 *           echo $a[bar];             输出 hello
 *           echo $a['bar'];           输出 hello  加不加单引号都是一样的
 *
 *
 *      11.  写出下面代码的执行结果
 *           for($i=0;i<10;$i++){
 *               print $i;
 *           }
 *           答案是死循环 因为中间的i 没有$
 *
 *      12.  对于echo ， 和 print 描述都正确的是
 *           echo 可以打印多个参数， print 只能打印一个参数
 *
 *
 *      13.  对于下面的代码
 *           $fruits = array('strawberry'=>'red','banana'=>'yellow');
 *          能够正确得到 yellow 的代码是
 *          A . echo "A banana is {$fruits['banana']}";
 *          B . echo "A banana is $fruits['banana']";
 *          c . echo "A banana is {$fruits[banana]}";
 *          D . echo "A banana is $fruits[banana]";
 *
 *         答案： b是得不到的 因为里面有了单引号 外面必须加花括号
 *
 *      14.  下面的代码执行完成之后的结果是什么
 *           function change(){
                static $i=0;
 *              $i++;
 *              return $i;
 *           }
 *          print change();
 *          答案  同名函数中的静态变量可以共享。  答案是1 和 2
 *
 *      15.   $foo = 'test';
 *            $bar = <<<EOT
 *              $foo bar
 *EOT;
 *      echo $bar;
 *      最后输出的结果  test bar ；  heredoc
 *
 *      16.   $a=3; $b=4;
 *      if($a || $b=5){
                echo 'tudo';
 *      }
 *      $b 的值是();  4
 *      答案 : 先看开关再看真假
 *
 *
 */

/**
 *     第五章：
 *          session 和cookie 的区别是什么
 *      答案 ： session 的安全性更高， session 存在于服务端 ， cookie 存在浏览器端
 *      session 依赖于cookie进行传输， 如果cookie 被禁用了 ，session 将不能在进行使用
 *
 *
 *      2.  http状态中 302是重定向 ，304 缓存未过期，
 *              403 服务器拒绝访问 ，404 访问的页面不存在 ，
 *              500 服务器内部错误
 *
 *      3.  linux 下面解压缩的命令
 *          tar czf test.tar.gz test.php   压缩
 *          tar vxzf  test.tar.gz          解压缩
 *
 *      4.  请问varchar 和char 的区别
 *          char(50)  定长  如果写个1 也是占位50个的空间
 *          varchar(50)   变长
 *
 *
 *      5. innodb 和 myisam 和区别
 *           innodb 支持事务机制 ，存储过程， 行级锁定等等
 *           myisam 增删改查的时候速度比较快 ， 但是数据量大的时候差别不大，myisam是表锁
 *
 *      6. 不使用cookie 如何向客户端发送一个cookie
 *           session_start();
 *           echo $sn = session_name();
 *           echo "<br/>";
 *           echo $sid = session_id();
 *           echo "<a href="test2.php?($sn)=($sid)">url传递session</a>";
 *
 *
 *          方法2： 该方法可以进行智能判断,如果客户端禁用了cookie则会这样
 *          php.ini:
 *              session.use_cookies = 1;   session 是否使用cookie
 *              session.use_trans_sid = 1;  让浏览器自动加上 sessionid = askdjf213dsakfj
 *
 *      7. isset() 和 empty() 的区别，对不同数据的判断结果
 *          isset : 若变量不存在则返回 false
 *                  若变量为null, 不管变量存在不存在 都是false
 *                  变量存在且其值不为null的时候 ，为true
 *                  如果变量使用unset()释放掉了之后， 则变量是false
 *          empty :
 *          若变量不存在则返回 TRUE
 *          若变量存在且其值为""、0、"0"、NULL、、FALSE、array()、var $var; 以及没有任何属性的对象，则返回 TURE
 *          若变量存在且值不为""、0、"0"、NULL、、FALSE、array()、var $var; 以及没有任何属性的对象，则返回 FALSE
 *
 *
 *      8. 如果在页面之间传递变量
 *          get post ajax curl cookie session
 *
 *      9. php 实现冒泡排序
 *          function getpao($arr){
 *              $len = count($arr);
 *              for($i = 1; $i<$len; $i++){
 *                  for($k = 0; $k<$len - $i;$k++){
 *                      if($arr[$k] > $arr[$k+1]){
 *                          $tmp = $arr[$k+1];
 *                          $arr[$k+1] = $arr[$k];
 *                          $arr[$k] = $temp;
 *                      }
 *                  }
 *              }
 *          }
 *
 *
 *      10. 在数据库中test中的一个表student，字段是name，class，score
 *         分别代表姓名，所在班级，分数。
 *          1. 查出每个班级的及格人数和不及格人数
 *          select class ,               //班级
 *          sum(if(score>=60,1,0)) jige,                             //几个人数
 *          sum(if(score<60,1,0)) bujige,                               //不及格人数
 *          from student group by class；
 *
 */



