<?php

use Workerman\Worker;

require_once __DIR__.'/../Workerman/Autoloader.php';

$http_word = new Worker('websocket://0.0.0.0:7845');

$http_word->count=1;

$http_word->onConnect = function($connection){
  $connection->send('some one login in.');
};
$http_word->onMessage= function($connection,$data){
    $connection->send('hello '.$data);
};
Worker::runAll();

