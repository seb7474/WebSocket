# Dimagine WebSocket

## Local running

- Run the server : `php -S localhost:8081 -t public`
- Run the chat : `php bin/chat-server.php`
- Run ngrok : `ngrok http 8080`

## Commands 

- To send a message : `{"command":"message","message":"testMessage"}`  
- To create or enter in a room : `{"command":"subscribe","channel":"nomDelaRoom"}`  
- To display the room list : `{"command":"list"}`
- To display pseudo or id (if no pseudo) of players in the room : `{"command":"roomPlayers"}`
- To choose or change a pseudo : `{"command":"pseudo","pseudo":"testPseudo"}`

