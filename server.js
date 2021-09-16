let server = require('http').Server();
let io = require('socket.io')(server);
redis = new require('ioredis')();
var activeUserArray = [];
var user;

io.sockets.on('connect', function (socket) {
    console.log('Successfully connected');
    user = socket.handshake.query.userId ;

    connectedUsers();

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
        console.log('disconnected')
        /** remove user from array **/
            let filter = activeUserArray.filter((e) => {
                return e!== socket.handshake.query.userId
            });
            activeUserArray = filter;
            io.sockets.emit('ConnectedUserArray',activeUserArray);
    });

});
server.listen(3000,() =>{
    console.log('Server started');
})
/** create connected user array **/
function connectedUsers(){
    if(!activeUserArray.includes(user)){
        activeUserArray.push(user)
    }
    io.sockets.emit("ConnectedUserArray", activeUserArray);
}



