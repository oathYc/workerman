<?php

use Workerman\Worker;
require_once './../Workerman/Mysql/Connection.php';

require_once __DIR__.'/../Workerman/Autoloader.php';
global $mysql;
$uid = 0;
define('HEARTBEAT_TIME',55);
//$mysql = new \Workerman\MySQL\Connection('127.0.0.1',3306,'root','root','renma');
$http_word = new Worker('websocket://0.0.0.0:8071');

$http_word->count=1;

$http_word->onConnect = function($connection){
    global $uid,$http_word;
    $uid += 1;
    $user = 'user'.$uid;
    $connection->uid = $user;
    foreach($http_word->connections as $conn){
        $data = ['userId'=>$user,'content'=>'我来了'];
        $data = json_encode($data);
        $conn->send($data);
    }
};
$http_word->onWorkerStart = function($http_word){
    \Workerman\Lib\Timer::add(3,function()use($http_word){
        $time = time();
        foreach($http_word->connections as $conn){
            if(!empty($conn->lastMessageTime )){
                if($conn->lastMessageTime  < ($time - HEARTBEAT_TIME)){//超过心跳时间
                    $conn->close();
                }
            }else{
                $conn->lastMessageTime  = $time;
                continue;
            }
        }
    });
};
$http_word->onMessage= function($connection,$data){
    global $http_word;
    $time = time();
    $connection->lastMessageTime  = $time;//记录最新发言时间
    foreach($http_word->connections as $conn){
        if(is_string($data)){
            $data = json_decode($data,true);
        }
        $send = ['userId'=>$connection->uid,'content'=>$data['content']];
        $send = json_encode($send);
        $conn->send($send);
    }
//    global $mysql;
//    $data = $mysql->select('title')->from('cy_product')->row();
//    $data = $mysql->select('title')->from('cy_product')->where("id > 34")->query();
//    $data = $mysql->insert('cy_product')->cols(['title'=>'ceshi','price'=>100])->query();
//    var_dump($data);
//    $connection->send($data);
};
$http_word->onClose = function($connection){
    global $http_word;
    foreach($http_word->connections as $conn){
        $data  = ['userId'=>$connection->uid,'content'=>'我走了，下次见'];
        $send = json_encode($data);
        $conn->send($send);
    }
};
Worker::runAll();

