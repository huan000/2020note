<?php
/**
 * 源代码安装php7 :
 *      ./configure --prefix=/usr/local/software/php7.3        //安装到哪个路径当中
 *
 * 查看php的拓展 ：
 *      ./bin/php -m
 *
 * 简化php命令 ：
 *      vi ~/bash/profile
 *      alias php7.3=/usr/local/software/php7.3/bin/php
 *
 * 配置php的配置文件:
 *      将源代码目录中的 php.ini  cp  到 php/etc程序当中
 *
 * 查看php.ini 文件的安装位置:
 *      php -i | grep php.ini
 */


/**
 *  源代码安装swoole
 *   下载 ： 通过clone 方式
 *
 *  进行编译成拓展 ：
 *   /usr/local/software/php7.3/bin/phpize
 *
 *  关联php:
 *   ./configure --with-php-config=/usr/local/software/php7.3/bin/php-config
 *   ./configure --with-php-config=/usr/local/software/php7.3/bin/php-config --enable-openssl --enable-http2 --enable-async-redis --enable-sockets  --enable-mysqlnd
 *
 *  make && make install
 *  拓展位置： /usr/local/software/php7.3/lib/php/extensions/no-debug-zts-20180731/
 *
 *
 *
 *
 *
 */
//
//./configure --prefix=/usr/local/software/php7.3 \
//--with-config-file-path=/usr/local/software/php7.3/etc \
//--with-fpm-user=www \
//--with-fpm-group=www \
//--with-mysqli=mysqlnd \
//--with-pdo-mysql=mysqlnd \
//--with-iconv-dir \
//--with-freetype-dir \
//--with-jpeg-dir \
//--with-png-dir \
//--with-zlib \
//--with-libxml-dir \
//--with-ldap=shared \
//--with-gdbm \
//--with-pear \
//--with-gettext \
//--with-curl \
//--with-xmlrpc \
//--with-openssl \
//--with-mhash \
//--with-gd \
//--enable-fpm \
//--enable-mysqlnd \
//--enable-mysqlnd-compression-support \
//--enable-xml \
//--enable-rpath \
//--enable-bcmath \
//--enable-shmop \
//--enable-sysvsem \
//--enable-inline-optimization \
//--enable-mbregex \
//--enable-mbstring \
//--enable-intl \
//--enable-ftp \
//--enable-gd-jis-conv \
//--enable-pcntl \
//--enable-sockets \
//--enable-zip \
//--enable-soap \
//--enable-fileinfo \
//--enable-opcache \
//--enable-maintainer-zts









