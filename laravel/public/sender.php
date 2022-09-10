<?php
require __DIR__.'/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(
    'localhost',
    5672,
    'guest',
    'guest'
);

try {
    $channel = $connection->channel();
    $channel->queue_declare('Coffee', false, false, false);
    $message = new \PhpAmqpLib\Message\AMQPMessage('latte');
    $channel->basic_publish($message, '','Coffee');
    $channel->close();
    $connection->close();
}catch (\AMQPException $exception){
    echo $exception->getMessage();
}
