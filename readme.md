# Dimagine WebSocket

## Local running

- Run the server : `php -S localhost:8081 -t public`
- Run the chat : `php bin/chat-server.php`
- Run ngrok : `ngrok http 8080`

## Commands 

- To send a message : `{"command":"message","message":"testMessage"}`  
- To create or enter in a room : `{"command":"subscribe","channel":"nomDelaRoom"}`  
- To exit a room : `{"command":"unsubscribe"}`  
- To display the room list : `{"command":"list"}`
- To display pseudo or id (if no pseudo) of players in the room : `{"command":"roomPlayers"}`
- To choose or change a pseudo : `{"command":"pseudo","pseudo":"testPseudo"}`

## Local Js script for testing 

``` var conn = new WebSocket('ws://1cb93a5e.ngrok.io/:8080');

conn.onopen = function(e) {
    console.log("Connection ok!");
};

conn.onmessage = function(e) {
    console.log(e.data);
};

function subscribe(channel) {
    conn.send(JSON.stringify({ command: "subscribe", channel: channel }));
}

function unsubscribe() {
    conn.send(JSON.stringify({ command: "unsubscribe" }));
}

function sendMessage(msg) {
    conn.send(JSON.stringify({ command: "message", message: msg }));
}

function addPseudo(pseudoP) {
    conn.send(JSON.stringify({ command: "pseudo", pseudo: pseudoP }));
}

function allName() {
    conn.send(JSON.stringify({ command: "roomPlayers" }));
}

function list()  {
    conn.send(JSON.stringify({ command: "list" }));
} 

```

