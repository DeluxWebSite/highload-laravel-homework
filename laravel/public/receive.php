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

    $message = new \PhpAmqpLib\Message\AMQPMessage('latte', 1);
    $channel->basic_publish($message);


    echo " [*] Waiting for messages. To exit press CTRL+C\n";

    $callback = function ($msg) {
        echo ' [x] Received ', $msg->body, "\n";
    };

    $channel->basic_consume('Coffee', '', false, true, false, false, $callback);

    while ($channel->is_open()) {
        $channel->wait();
    }
}catch (\AMQPException $exception){
    echo $exception->getMessage();
}
