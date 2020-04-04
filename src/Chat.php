<?php
namespace App;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;


class Chat implements MessageComponentInterface,WampServerInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);


        echo "New connection! ({$conn->resourceId})\n";

        foreach ($this->clients as $client) {

            $client->send("Bravo Yohan tu es connecté avec l'ID : {$conn->resourceId}");

        }
        $this->onCall($conn,$conn->resourceId,"toto",[]);


    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send("Toto send : " . $msg);


            }
        }


    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";

    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }


    public function onSubscribe(ConnectionInterface $conn, $topic) {
        echo "topic: ".$topic;
    }
    public function onUnSubscribe(ConnectionInterface $conn, $topic) {}


    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        $conn->callError($id, $topic, 'RPC not supported on this demo');
        echo "call";
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        $topic->broadcast($event);
    }


}