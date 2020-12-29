<?php
/**
 * 安装ab工具：
 *      sudo yum -y install httpd-tools     安装
 *      which ab                            查看ab工具的安装位置
 *      ab -V                               查看ab工具的版本
 *
 * 安装phpredis
 *      1.  php7.2.9/bin/phpize
 *      2.  ./configure --with-php-config=PATH
 *      3.  make -j  && make install
 *      4.  php.ini             php-m
 *
 *  安装yaconf
 *      1.  php7.2.9/bin/phpize
 *      2.  ./configure --with-php-config=PATH
 *      3.  make -j  && make install
 *      4.  php.ini
 *          extension=yaconf
 *          yaconf.directory=CONFDIR
 *      设置conf
 *      vim redis.ini
 *          host="127.0.0.1"
 *          port=6379  ...
 *
 *      获取conf
 *      \Yaconf::get('redis.ini');
 *
 *  nginx 端口转发：
 *      if(!-e $request_filename){
 *          proxy_pass http://127.0.0.1:8000
 *      }
 *
 */

/**
 *  easyswoole 视频上传
 *      public function file(){
                $request = $this->request();
 *              $video = $request->getUploadFile("file");        //接收视频上传信息
 *              $video->moveTo("/home/work/htdocs");            //进行移动上传
 *      }
 *
 *
 *  反射机制实例化相关的类 *************
 *
 *
 */










