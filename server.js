let server = require('http').Server();
let io = require('socket.io')(server);
redis = new require('ioredis')();
var array = [];
var user;

io.sockets.on('connect', function (socket) {
    console.log('Successfully connected');
    user = socket.handshake.query.userId ;

    if(!array.includes(user)){
        array.push(user)
    }
    console.log(array)
    io.sockets.emit("ConnectedUserArray", array);
    redis.subscribe('chat_app:channel');

    redis.on("message", function (channel, data) {
        var obj = JSON.parse(data)

        if (obj.event == 'remove') {
            socket.emit('remove', obj);
        }
        if (obj.event == 'send') {
            socket.emit('chat_message', obj);
        }
        if (obj.event == 'activeUsers') {
            socket.emit('activeUsers', obj);
        }
    });

    socket.on('disconnect', () => {
        let userid = array.indexOf(user);
        array.splice(userid,1)
        io.sockets.emit('ConnectedUserArray',array);
        console.log(array)
    });

});

server.listen(3000,() =>{
    console.log('Server started');
})



