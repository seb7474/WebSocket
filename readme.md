# Dimagine WebSocket

## Local running

- Run the server : `php -S localhost:8081 -t public`
- Run the chat : `php bin/chat-server.php`
- Run ngrok : `ngrok http 8080`

## Commands 

- To send a message : `{"command":"message","message":"testMessage"}`  
- To create or enter in a room : `{"command":"subscribe","channel":"nomDelaRoom"}`  
- To display the room list : `{"command":"list"}`

Don't use the "unsubscribe" command for the moment : `{"command":"unsubscribe","channel":"nomDelaRoom"}`