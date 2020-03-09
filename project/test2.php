<?php

use Workerman\Worker;

require_once __DIR__.'/../Workerman/Autoloader.php';

$global_uid = 0;
//当客户端连接时分配uid，并保存连接，通知所有客户端
function handle_connection($connection){
    global $test_worker,$global_uid;
    $connection->uid = ++$global_uid;
}
//当客户端发送消息时，转发给所有人
function handle_message($connection,$data){
    global $test_worker;
    foreach($test_worker->connections as $conn){
        if(is_array($data)){
            $data = json_encode($data);
        }
        $conn->send("user[$connection->uid] said $data");
    }
}
//当客户端断开连接时，通知所欲客户端
function handle_close($connection){
    global $test_worker;
    foreach($test_worker->connections as $conn){
        $conn->send("user[$connection->uid] logout");
    }
}

//创建一个协议的worker监听23454端口
$test_worker = new Worker('text://0.0.0.0:2354');

//只启动一个进程 方便客户端传送数据
$test_worker->count = 1;
$test_worker->onConnect = 'handle_connection';
$test_worker->onMessage = 'handle_message';
$test_worker->onClose = 'handle_close';

Worker::runAll();