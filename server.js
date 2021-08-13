let server = require('http').Server();
let socket = require('socket.io')(server);
redis = new require('ioredis')();

server.listen(3000)
console.log('Server started');

socket.on('connection',function(io) {
    console.log('Successfully connected');

    redis.subscribe('chat_app:channel');

    redis.on("message", function(channel, data) {
        io.emit('chat_message',data);
    });
});

