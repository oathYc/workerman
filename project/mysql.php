<?php

use Workerman\Worker;
require_once './../Workerman/Mysql/Connection.php';

require_once __DIR__.'/../Workerman/Autoloader.php';
global $mysql;
$mysql = new \Workerman\MySQL\Connection('127.0.0.1',3306,'root','root','renma');
$http_word = new Worker('websocket://0.0.0.0:7865');

$http_word->count=1;

$http_word->onConnect = function($connection){
    $data = ['userId'=>'系统','content'=>'系统就位'];
    $data = json_encode($data);
    $connection->send($data);
};
$http_word->onMessage= function($connection,$data){
//    global $mysql;
//    $data = $mysql->select('title')->from('cy_product')->row();
//    $data = $mysql->select('title')->from('cy_product')->where("id > 34")->query();
//    $data = $mysql->insert('cy_product')->cols(['title'=>'ceshi','price'=>100])->query();
//    var_dump($data);
    $connection->send($data);
};
Worker::runAll();

