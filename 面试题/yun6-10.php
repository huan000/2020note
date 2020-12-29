<?php
/**
 *  第六讲：
 *      面向对象中接口和抽象类的区别是什么：
 *      区别 ：
 *      抽象类中可以有非抽象方法， 接口中只能有抽象方法
 *      一个类可以实现多个接口，而一个类只能继承一个抽象类
 *      接口中定义的所有方法都是公有的，抽象类中的抽象方法只要不是私有的就可以
 *1、抽象类可以有属性、普通方法、抽象方法，但接口不能有属性、普通方法、可以有常量

2、抽象类内未必有抽象方法，但接口内一定会有“抽象”方法

3、语法上有不同

4、抽象类用abstract关键字在类前声明，且有class声明为类，接口是用interface来声明，但不能用class来声明，因为接口不是类。

5、抽象类的抽象方法一定要用abstract来声明，而接口则不需要

6、抽象类是用extends关键字让子类继承父类后，在子类实现详细的抽象方法。而接口则是用implements让普通类在类里实现接口的详细方法，
 * 且接口可以一次性实现多个方法，用逗号分开各个接口就可
 *
 *      抽象类 ：
 *      1.定义为抽象的类不能够别实例化，任何一个类 如果它里面有一个方法别声明为抽象的
 *      那么这个类必须被声明为抽象类
 *      2.继承一个抽象类的时候，子类必须实现抽象类中的方法，这些方法的访问控制符号只能
 *      更加宽松。
 *      3. 抽象类可以有成员属性
 *      4. 抽象类中的方法不能定义为私有的
 *      5. 抽象类可以继承抽象类，但是不能重写抽象父类的抽象方法，这样的用法可以定义为
 *      抽象类的拓展
 *      6. 抽象类可以实现接口，且可以不实现其中的方法
 *
 *      接口：
 *      1. 接口中定义的所有的方法都是共有的
 *      2. 抽象类实现接口可以不重写其中的方法
 *      3. 实现多个接口的时候，接口中的方法不能有重名情况
 *      4. 接口可以有常量，不可以有属性
 *
 *
 *      接口的实现和继承：
 *          一个接口可以继承自另外的接口。要注意只有接口和接口之间使用继承关键字extends。
            一个接口继承其他接口时候，直接继承父接口的静态常量属性和抽象方法。
            类实现接口必须实现其抽象方法，使用实现关键字implements。
            PHP中的类是单继承，但是接口很特殊。一个接口可以继承自多个接口。
            抽象类实现接口，可以不实现其中的抽象方法，而将抽象方法的实现交付给具体能被实例化的类去处理。
 *
 *
 *
 *      增加一个字段性别sex，写出修改语句
 *      alter table user add sex varchar(10) not null default "男";
 *
 *      tinyint -128 127    1字节
 *      int  -21亿  21亿    4字节
 */


/**
 *  第七集：
 *      $test = 'aaaaa';
 *      $abc = &$test;
 *      unset($test);
 *      echo $abc;
 *      答案： aaaaa ； 因为unset删除的是 $test 和 aaaaa 之间的引用 ，没有删除abc 和aaaaa
 *          之间的引用
 *
 *
 *     在空表news中字段id为主键自增，批量插入17条记录之后，发现最后的三条记录有误
 *      删除这三条记录后再重启mysql数据库，重新插入三条记录 ，请问最后一条记录的id值是多少
 *
 *     写出一个验证139开头的11位手机号码的正则表达式
 *      /^139\d{8}$/
 *
 *
 *      写一个函数将open_door 转换成 OpenDoor
 *      function change($str){
                str_replace(' ','',ucwords(str_replace('_',' ',$str)));
 *      }
 *
 *
 *      写一个函数 将123456789 转换成 1,234,567,890
 *      number_format($num);
 *      方法2 ：
 *          strrev($str);
 *          str_split($str,3);
 *          strrev(join(',',$arr));
 *
 *
 *      有a表(id,sex,par,c1,c2),b(id,age,c1,c2)两张表，其中a.id与b.id关联，
 *      现在要求写一条sql语句，将b中的age>50 的记录的c1，c2 更新到a表中的c1，c2字段中
 *
 *      update A,B set A.c1=B.c1,A.c2=B.c2 where A.id=B.id and b.age>50;
 *
 *
 *      实现每天12点的时候重新启动服务器
 *      Crontab -e
 *      00 00 * * * /sbin/reboot
 *
 *
 *      当前目录下有一个文件为showme.sh,如何修改文件，将其指定为使用 /bin/bash 运行
 *      如何修改其权限为所有用户可读写，所有用户可以执行
 *      vi showme.sh
 *      #!/bin/bash
 *      chmod 777 showme.sh
 *
 *      修改这个文件的所有人为root ： chown root showme.sh
 *      修改这个文件的所属组为root ： chown :root showme.sh
 *
 *
 *      如何抓取一个网页：
 *      file_get_contents('http://www.baidu.com');
 *      方法2：
 *      $fp = fopen('http://www.baidu.com','r');
 *      $str = '';
 *      while(!feof($fp)){
            $str .= fread($fp,'1024');
 *      }
 *
 *
 *      如何匹配出这个网页的标签
 *      preg_match('/<title>(.+)</title>/',$str,$ms);
 *      echo $ms[1];
 *
 */


/**
 *   第八集：
 *      $dir = 'vow';
 *      function dirList($dir){
 *          $arr = scandir($dir);
 *          foreach($arr as $val){
                if($val !='.' && $val !='..'){
 *                      $path = $dir.'/'.$val;
 *                      if(is_dir($path)){
                            //是文件夹
 *                          dirList($path);
 *                      }else{
                            echo $path;
 *
 *                      }
 *              }
 *          }
 *      }
 *
 *
 *      $dir = 'vow';
 *      function dirList($dir){
            $arr = scandir($dir);
 *          foreach($arr as $val){
                if($val !='.' && $val !='..'){
 *                  $path = $dir.'/'.$val;
 *                  if(is_dir($path)){
                        dirList($path);
 *                  }else{
 *                      echo $path;
 *                  }
 *              }
 *          }
 *      }
 *
 *
 *     用js实现前进和后退：
 *      <a href='javascript:history.go(-1)'>后退</a>
 *
 *     假设mysql的用户数在千万级别 怎么办
 *
 *     写出以下程序的结果
 *      $a = 0;
 *      $b = 0;
 *      if($a = 3 || $b =3){
            $a ++;
 *          $b ++;
 *      }
 *      echo $a.','.$b;
 *
 *      答案是 1,1 ; 先运行赋值等号右边的东西
 *
 *
 */


/**
 *  第九集：
 *      用php打印出前一天的时间格式 2006-5-10 22：22：22
 *          date('Y-n-d H:i:s',strtotime('-1 day'));
 *
 *      echo,print,print_r 的区别：
 *      echo 是语言结构
 *      print 是语言结构     只能打印出简单类型的变量
 *      print_r 是函数(有返回值)   也可以打印出复合类型的变量
 *
 *      printf 和 sprintf 的区别：
 *      printf 是格式化输出变量
 *      sprintf 是格式化返回变量
 *
 *
 *      mysql 获得当前日期时间的函数是 now()
 *            格式化日期的函数是 date()
 *
 *      获得服务器端的地址：
 *      $_SERVER['REMOTE_ADDR']
 *      获得服务器端的地址
 *      $_SERVER['SERVER_ADDR']
 *
 *
 *      include 和 require 的区别
 *      include 如何包含不成功 会抛出警告错误 下文会继续执行
 *      require 如果包含文件不成功  会抛出致命错误  下文不会执行
 *
 *      如何修改session的生存时间
 *      session.cookie_lifetime = 0;   控制的是保存sessionid 的cookie的生命时间，设置成0就是
 *                                  随着浏览器的关闭而关闭
 *      session.gc_maxlifetime = 1440
 *
 *      在http1.0中，状态码401的含义是 代表未授权
 *      返回找不到文件的提示 用的header函数是
 *      header("HTTP/1.0 404 找不到文件");
 *
 *      写出发帖数最多的十个人的名字的sql。
 *      members(id,username,posts,pass,email);
 *      select username,count(id) from members group by username order by count(id)
 *      desc limit 10;
 *
 *      第十集：
 *      error_reporting: 设置错误级别
 *      E_ALL
 *      E_PARSE
 *      E_ERROR
 *      E_WARNING
 *      E_NOTICE
 *
 *      如何得到当前文件的脚本路径和脚本参数
 *      脚本路径： $_SERVER['SCRIPT_FILENAME']
 *      脚本参数： $_SERVER['QUERY_STRING']
 *
 *      打印出字符串中的第一个字母
 *      $str = 'abcdef';
 *      echo $str[0];
 *      echo $str{0};  // 以上两种都可以
 *
 *      
 *      下面哪一个函数可以打开一个文件，可以对文件进行读和写的操作
 *      fopen();
 *
 *      下面哪一个选项没有将join添加到数组中
 *      a. $users[] = 'john';                 可以
 *      b. array_add($users,'john');          没有该函数
 *      c. array_push($users,'john');         可以
 *      d. $users ||= 'john' : [a,c];
 *
 *
 *      把sql表里面的张三的时间更新为系统的时间
 *      $nowdate = date('Ymd');
 *      "update `user` set date='".$nowdate."' where name='张三'";
 *
 *      删除张四的所有记录
 *      delete from `user` where name='张四'；
 *
 *
 *
 *
 *
 *
 *
 *
 */