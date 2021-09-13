let server = require('http').Server();
let io = require('socket.io')(server);
redis = new require('ioredis')();
var array = [];
var user;

io.sockets.on('connect', function (socket) {
    console.log('Successfully connected');
    user = socket.handshake.query.userId ;

    redis.subscribe('chat_app:channel');

    redis.on("message", function (channel, data) {

        var obj = JSON.parse(data)
        if (obj.event == 'activeUsers') {
            socket.emit('activeUsers', obj);
        }
        if (obj.event == 'remove') {
            socket.emit('remove', obj.id);
        }
        if (obj.event == 'send') {
            socket.emit('chat_message', obj);
        }

    });

    socket.on('disconnect', () =>{
            let newArr = array.filter((e) => {
                return e!== socket.handshake.query.userId
            });
            array = newArr;
            io.sockets.emit('ConnectedUserArray',array);
    });

    connectedUsers();
});

server.listen(3000,() =>{
    console.log('Server started');
})
function connectedUsers(){
    if(!array.includes(user)){
        array.push(user)
    }
    io.sockets.emit("ConnectedUserArray", array);
}



