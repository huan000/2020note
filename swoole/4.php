<?php
/**
 *  4-1：创建tcp服务器
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
/**
 *  创建tcp服务器：
 *      $serv = new swoole_server("127.0.0.1",9501);
 *  绑定启动参数：
 *      $serv->set([
 *          "worker_num"=>8,                // 开启的worker的进程数
 *          "max_request" => 10000,         // 最大的链接数
 *      ]);
 *  监听接入：
 *      $serv->on("connect",function($serv,$fd,$reactor_id){
            echo "client:connect";
 *      });
 *  监听接收：
 *      $serv->on("receive",function($serv,$fd,$reactor_id,$data){
            $serv->send($fd,"server:",$data);
 *      });
 *  监听链接关闭：
 *      $serv->on("close",function($serv,$fd){
            echo "client close";
 *      });
 *
 *  启动服务器：
 *      $serv->start();
 *
 */

/**
 *TCP客户端：
 *  创建客户端
 *      $client = new swoole_client(SWOOLE_SOCK_TCP);
 *      $client->connect("127.0.0.1",9501);
 *
 *  //发送数据
 *      $client->send($msg);
 *
 *  //接收数据
 *      $result = $client->recv();
 */

/**
 *  udp服务端：
 *  自行完成；
 */

/**
 *  HTTP 服务端：
 *  $http = new swoole_http_server("0.0.0.0",8811);
 *
 *  $http->set([
 *      "enable_static_handler" => true,                //是否可以访问静态资源
 *      "document_root" => "PATH",                      //静态资源的存放地址
 * ]);
 *
 *  // 第一个参数是请求  第二个参数是响应
 *  $http->on("request",function($request,$response){
 *      $request->get;              //获取参数
 *      $response->cookie("singwa","xssss",time() + 1800);      //设置cookie
 *      $response->end("响应的主体");
 *  });
 *  // 开启http服务
 *  $http->start();
 *
 */

/**
 *  websocket 服务：
 *      websocket 协议是基于tcp的一种新的网络协议，它实现了浏览器于服务器全双工
 *      的通信，允许服务器主动发送信息给客户端
 *
 *  服务端：　
 *      $server = new swoole_websocket_server("0.0.0.0",9501);
 *      $server->on("open",function(swoole_websocket_server $server,$request){
            echo "握手成功";
 *      });
 *
 *      $server->on("message",function(swoole_websocket_server $server,$frame){
            $server->push($frame->fd,"this is from server");
 *      });
 *
 *      $server->on("close",function($ser,$fd){
            echo "client {$fd} closed\n";
 *      });
 *
 *      $server->on("request",function);
 *
 *  *  websocket 前端代码
 *      <script>
 *          var wsUrl = "ws://singwa.swoole.com:8812";
 *          var websocket = new WebSocket(weUrl);
 *          //实例对象的onopen属性
 *          websocket.onopen = function (evt){
 *              //发送一个数据
 *              websocket.send("hello-sinwa");
                console.log("connect");
 *          }
 *          //实例化 onmessage  接受服务端的数据
 *          websocket.onmessage = function (evt){
 *              console.log("ws-server-return-data"+evt.data);
 *          }
 *          //onclose
 *          websocket.onclose = function (evt){
                console.log("close");
 *          }
 *          //onerror
 *             websocket.onerror = function (evt){
                console.log("error"+ evt.data);
 *          }
 *      </script>
 *
 */


/**
 *  swoole Task 任务使用
 *      使用场景 ： 执行耗时的操作(发送邮件 广播等)
 *      示例 ： 假如子啊
 *
 *      public function onTask($serv,$taskId,$workerId,$data){
 *      }
 *
 */


/**
 *  异步毫秒定时器：
 *      2秒进行一次执行
 *      swoole_timer_tick(2000,function{
 *          echo '2';
 *      });
 *
 *      5秒之后发送一个消息
 *       swoole_timer_after(5000,function()use($ws,$frame){
 *          echo "5s-after";
 *      });
 *
 */

/**
 *  异步文件系统
 *      异步读取文件：
 *      swoole_async_readfile($filename,function($filename,$filecontent){});
 *      异步读取大文件：
 *
 *      异步写入文件：
 *      swoole_async_writefile($filename,$content,function($filename){});
 */

/**
 *  异步mysql客户端：
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

/**
 *  进程管理：
 *      // 第二个参数如果是true 子进程的信息不会打印到屏幕上去
 *      $process =  new swoole_process(function(swoole_process){

 *      },true);
 *
 *      $process->start();
 *
 *
 *
 *
 *
 *
 *
 *
 */
