<?php

namespace App\Components;

use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\Message;
use Ratchet\WebSocket\MessageComponentInterface;
use SplObjectStorage;

class Messenger implements \Ratchet\MessageComponentInterface
{
    protected \SplObjectStorage $clients;
    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
    }


    function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection! {$conn->resourceId}\n";
    }

    function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Close connection! {$conn->resourceId}\n";
    }

    function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error: {$e->getMessage()}\n";
    }

    function onMessage(ConnectionInterface $conn, $msg)
    {
        $clientCount = $this->clients->count() ;
        echo sprintf(
            'Connection %d sending message "%s" to %d other connection%s\n',
            $conn->resourceId,
            $msg,
            $clientCount,
            $clientCount === 1 ? '' : 's'
        );

        foreach ($this->clients as $client)
        {
            if ($conn !== $client)
            {
               $client->send($msg);
            }
        }
    }
}
