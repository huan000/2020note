<?php
/**
 *
 *
 *1. 常用端口：
 *      21 ： ftp服务端口
 *      22 ： ssh服务端口
 *      23 ： telnet 服务端口
 *      25 ： 发邮件端口
 *      53 ： dns 服务
 *      80 ： web 服务
 *      110 ： 收邮件服务
 *      443 ： ssl
 *      3306 ： mysql 服务端口
 *      6379 ： redis 服务端口
 *      11211 ： memcache 服务端口
 *
 *
 * 2. linux 的部分文件
 *      /etc/passwd   存储用户信息
 *      /etc/hosts    存放本机域名映射
 *      /etc/fstab    存放磁盘分区信息配置的
 *      crontab       计划任务
 *      sshd          远程登陆
 *
 *
 * 3. 把左连接中产生的null 替换成 0 或者 ""
 *      select t4.name,if(t5.tel is null,'',t5.tel) from test_4 t4 left join
 *      test_5 t5 on t4.id=t5.uid
 *
 *
 *    数组相加问题：
 *      $arr1 = array(1,2,3);
 *      $arr2 = array(4,5,6,7,8);
 *      $arr3 = $arr1 + $arr2;     结果：以数组arr1为基准， array(1,2,3,7,8);
 *
 *
 * 4. php 预定义全局变量
 *      $GLOBALS ,$_POST, $_GET $_FILES ,$_REQUEST , $_SESSION ,$_COOKIE, $_SERVER
 *
 *    php 预定义常量
 *      __LINE__ , __FILE__ , __DIR__ , __FUNCTION__ , __CLASS__, __NAMESPACE__
 *
 *
 * 5. 写一个函数实现字符串反转
 *      strrev();
 *      function rev($str){
 *           $len = strlen($str);
 *          for($i=$len;$i>=0;$i--){
 *               echo $str{$i};
 *          }
 *      }
 *
 *
 * 6. 不断的在hello.txt 文件头部写入一行 "hello world";
 *      前追加案例 ：
 *      $str = "hello world";
 *      $handle = fopen("hello.txt","r+");
 *      fwrite($handle,$str);
 *
 *      fopen参数解释：　
 *          r: 可读
 *          w: 清空写
 *          a: 后追加
 *
 *      后追加案例：
 *      file_put_contents("file.txt","hello world",FILE_APPEND);
 *
 *
 * 7. 将一个二维数组按照 name的长度进行排序
 *      $arr =  array(
            array('id'=>0,'name'=>'123'),
            array('id'=>0,'name'=>'123213'),
            array('id'=>0,'name'=>'12321'),
            array('id'=>0,'name'=>'123123123'),
            array('id'=>0,'name'=>'123123'),
            array('id'=>0,'name'=>'12322'),
            array('id'=>0,'name'=>'1231111'),
 *      );
 *
 *      foreach($arr as $val){
            $arr2[] = strlen($val[name]);
 *      }
 *
 *      array_multisort($arr2,$arr);
 *
 *
 *
 *  12课：
 *     1. 实现不使用第三个变量，交换$a,$b 的值，$a,$b 的初始值自己定
 *          $a = linux;
 *          $b = php;
 *          $b = array($a,$b);
 *          $a = $b[1];
 *          $b = $b[0];
 *
 *      2. 如何从一个url里面取得文件的拓展名
 *          $url = 'http://www.sina.com.cn/abc/de/fg.php?id=1';
 *          $arr = parse_url($url);
 *          结果 ：
 *          array(
                scheme=>http,
 *              host=>www.sina.com.cn,
 *              path=>/abc/de/fg.php,
 *              query=>id=1
 *          );
 *          $arr2 = pathinfo(arr[path]);
 *          结果：
 *          array(
                dirname => /abc/de,
 *              basename => fg.php,
 *              extension => php
 *              filename=>fg
 *          );
 *
 *      3. 下面程序输出的是什么
 *          $int_a = 5;
 *          function fac(){
                for($int_i = $int_a;$int_i>0;$int_i--){
 *                  $int_a = $int_a * $int_i;
 *              }
 *          }
 *          fac();
 *          echo $int_a;
 *          答案 ： 还是5  因为函数里面没有global 和 & 符号； 属于局部变量和全局变量是
 *          两个事
 *
 *      4. 匹配一个ip地址
 *      $ip = '192.168.21.2';
 *      $ptn = '/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/';
 *      preg_match($ptn,$ip,$arr);
 *
 *      5. 利用快速排序法对一个数组进行排序
 *      function quick($arr){
            $left = array();
 *          $right = array();
 *          if(count($arr) <= 1){
                return;
 *          }
 *          for($i=1;$i<count($arr);$i++){   从第二个开始循环
                if($arr[0] > $arr[$i]){      第一个和第二个进行比较
 *                  $left[] = $arr[$i];      如果第二个小放入left数组里面
 *              }else{
 *                  $right[] = $arr[$i];     如果第二个大放入right数组里面
 *              }
 *          }
 *          $left1 = quick($left);
 *          $right1 = quick($right);
 *          return array_merge($left1,array($arr[0]),$right1);
 *      }
 *
 *      6. 在开发项目中，需要上传超过8m的文件，需要修改php.ini的哪个配置
 *          upload_max_filesize();   文件上传大小 默认200
 *          post_max_size            表单默认的上传大小 默认256
 *
 *      7. 写出session 和 cookie的区别
 *          session 和 cookie 最大的区别是session 是存在服务器端的，cookie是存在
 *          浏览器端的，session是基于访问的进程，记录了一个访问的开始到结束，当浏览器
 *          或者进程进行关闭后，session也就消失了，而cookie是长久存在的
 *
 *      8. 如何解决session 共享的问题
 *          可以把sessionid存在数据库中
 *          可以存储在memcached，reids，mongodb中
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