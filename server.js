var app = require('express'); //es raari expresii :D istarteba ase ? e
var server = require('http').createServer(app);
var io = require('socket.io')(server);
var redis = require('redis');
/**
 * sxva filebi sadaa ra failebi chatis
 */
server.listen(6379);
io.on('connection', function (socket) { //save

    console.log("client connected");
    var redisClient = redis.createClient();
    redisClient.subscribe('message');

    redisClient.on("message", function(channel, data) {
        socket.emit(channel, data);
    });

    socket.on('disconnect', function() {
        redisClient.quit();
    });

});
