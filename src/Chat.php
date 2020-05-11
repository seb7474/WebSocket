<?php

namespace App;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;


class Chat implements MessageComponentInterface
{
    protected $clients;
    private $subscriptions;
    private $users;
    private $pseudos;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->subscriptions = [];
        $this->users = [];
        $this->pseudos = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        $this->users[$conn->resourceId] = $conn;


        echo "Nouvelle connexion! ({$conn->resourceId})\n";

        foreach ($this->clients as $client) {

            $client->send("Nouvelle connexion : {$conn->resourceId}");

        }
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        /**   foreach ($this->clients as $client) {
         * if ($from !== $client) {
         * $client->send(" send ".$from->resourceId.": " . $msg);
         * }
         * if ($from == $client) {
         * $client->send("Vous avez bien envoyer : " . $msg);
         * }
         * } **/

        $data = json_decode($msg);
        switch ($data->command) {

            case "list":


                foreach ($this->clients as $client) {
                    if ($from == $client) {
                        $returnString = "";
                        foreach ($this->subscriptions as $room) {
                            $returnString = $returnString . $room;
                        }

                        if ($returnString != "") {
                            $client->send($returnString);
                        } else {
                            $client->send("Aucune room");
                        }

                    }
                }

                break;

            case "subscribe":
                $this->subscriptions[$from->resourceId] = $data->channel;
                break;

            case "pseudo":
                $this->pseudos[$from->resourceId] = $data->pseudo;
                break;

            case "unsubscribe":
                unset($this->users[$from->resourceId]);
                unset($this->subscriptions[$from->resourceId]);
                break;

            case "message":
                if (isset($this->subscriptions[$from->resourceId])) {

                    $target = $this->subscriptions[$from->resourceId];

                    foreach ($this->subscriptions as $id => $channel) {

                        if ($channel == $target && $id != $from->resourceId) {

                            if (isset($this->pseudo[$from->resourceId])) {

                                if($this->pseudo[$from->resourceId] != ""){
                                    $name = $this->pseudo[$from->resourceId];
                                }else{
                                    $name = $from->resourceId;
                                }
                                $sendContent = $name . " dit : " . $data->message;

                                $this->users[$id]->send($sendContent);

                            }else{

                                $name = $from->resourceId;
                                $sendContent = $name . " dit : " . $data->message;
                                $this->users[$id]->send($sendContent);


                            }
                        }
                    }
                }
        }


    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        unset($this->users[$conn->resourceId]);
        unset($this->subscriptions[$conn->resourceId]);

        echo "Connection {$conn->resourceId} has disconnected\n";

    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }


}