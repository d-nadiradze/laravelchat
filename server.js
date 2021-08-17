let server = require('http').Server();
let socket = require('socket.io')(server);
redis = new require('ioredis')();

server.listen(3000)
console.log('Server started');

socket.on('connection',function(io) {
    console.log('Successfully connected');

    redis.subscribe('chat_app:channel');

    redis.on("message", function(channel, data) {
        var obj = JSON.parse(data)

        if(obj.event == 'remove') {
            io.emit('remove',obj);
        }
        if(obj.event == 'send') {
            io.emit('chat_message', obj);
        }



    });
});

