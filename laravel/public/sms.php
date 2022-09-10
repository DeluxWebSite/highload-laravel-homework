<?php

require __DIR__.'/../vendor/autoload.php';

use App\Components\Messenger;
use Ratchet\Server\IoServer;


$server = \Ratchet\Server\IoServer::factory(
    new \App\Components\Messenger(),
    8181
);

$server->run();
