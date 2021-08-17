
    $("#chat").scrollTop($("#chat")[0].scrollHeight);


    var socket = io.connect('http://localhost:3000', {transports: ['websocket']});
    let current = "{{Auth::user()->name}}";

    socket.on('chat_message', function (data) {
    data = jQuery.parseJSON(data);
    if (data['user'] == current) {
    $("#sms").append(
    "<li class='send'>"+
    "<div class='chat-message mt-3'>" +
    "<div class='text-gray-500 text-xs ml-11'>" + data.user + "</div>" +
    "<div class='flex items-end'>" +
    "<div class='flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 items-start'>" +
    "<div>" + "<span class='px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600'>" + data.message + "</span>" + "</div>" +
    "</div>" +
    "<img src='https://images.unsplash.com/photo-1549078642-b2ba4bda0cdb?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144' alt='My profile' class='w-6 h-6 rounded-full order-1'>" +
    "</div>" +
    "</div>"+
    "</li>"
    );
} else {
    $("#sms").append(
    "<li class='send'>"+
    "<div class='chat-message mt-3'>" +
    "<div class='text-gray-500 flex flex items-end justify-end mr-11 text-xs'>" + data.user + "</div>" +
    "<div class='flex items-end justify-end'>" +
    "<div class='flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end'>" +
    "<div class='px-4 py-2 rounded-lg inline-block rounded-br-none bg-blue-600 text-gray-100'>" +
    data.message +
    "</div>" +
    "</div>" +
    "<img src='https://images.unsplash.com/photo-1590031905470-a1a1feacbb0b?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144' alt='My profile' class='w-6 h-6 rounded-full order-2'>" +
    "</div>" +
    "</div>"+
    "</li>"
    );

}
    $("#chat").scrollTop($("#chat")[0].scrollHeight);


});


    $("#s").keypress(function (e) {
    if (e.which == 13) {
    e.preventDefault();
    var _token = $("input[name='_token']").val();
    var user = $("input[name='user']").val();
    var message = $("input[name='message']").val();
    if (message != '') {
    $.ajax({
    type: "POST",
    url: '{!! URL::to("sendmessage") !!}',
    dataType: "json",
    data: {'_token': _token, 'message': message, 'user': user},
    success: function (data) {
    $("input[name='message']").val('');
}
});
}
}
})

    $("#send-message").click(function (e) {
    e.preventDefault();
    var _token = $("input[name='_token']").val();
    var user = $("input[name='user']").val();
    var message = $("input[name='message']").val();
    if (message != '') {
    $.ajax({
    type: "POST",
    url: '{!! URL::to("sendmessage") !!}',
    dataType: "json",
    data: {'_token': _token, 'message': message, 'user': user},
    success: function (data) {
    $("input[name='message']").val('');
}
});
}

})

    $(document).ready(function () {
    $.ajax({
        method: "GET",
        url: "/messages",
        headers: {
            'Access-Control-Allow-Origin': '*'
        }
    }).done((r) => {
        var messagesLength = 0;
        for (let i=0;i<r.length; i++){
            messagesLength+=r[i].length;
        }
        $("#chat").scrollTop($("#chat")[0].scrollHeight);

        $('#chat').scroll(function () {
            console.log($('#chat').scroll+" ")
            if ($('#chat').scrollTop() == 0) {
                setTimeout(function () {
                    let currentLength = $(".send").length
                    if ((r.length - currentLength) < 20) {
                        var size = r.length - currentLength;
                    } else {
                        var size = 20;
                    }
                    var currentArray = []
                    for (let i = currentLength; i < currentLength + size; i++) {
                        currentArray.push(r[i]);
                    }
                    currentArray.forEach((data, j) => {
                        if (messagesLength == $('.send').length) {
                        } else {
                            if (data.username == current) {
                                $("#sms").prepend("<li class='send'>" +
                                    "<div class='chat-message mt-3'>" +
                                    "<div class='text-gray-500 text-xs ml-11'>" + data.username + "</div>" +
                                    "<div class='flex items-end'>" +
                                    "<div class='flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 items-start'>" +
                                    "<div>" + "<span class='px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600'>" + data.message + "</span>" + "</div>" +
                                    "</div>" +
                                    "<img src='https://images.unsplash.com/photo-1549078642-b2ba4bda0cdb?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144' alt='My profile' class='w-6 h-6 rounded-full order-1'>" +
                                    "</div>" +
                                    "</div>" +
                                    "</li>")
                            } else {
                                $("#sms").prepend("<li class='send'>" +
                                    "<div class='chat-message mt-3'>" +
                                    "<div class='text-gray-500 flex flex items-end justify-end mr-11 text-xs'>" + data.username + "</div>" +
                                    "<div class='flex items-end justify-end'>" +
                                    "<div class='flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end'>" +
                                    "<div class='px-4 py-2 rounded-lg inline-block rounded-br-none bg-blue-600 text-gray-100'>" +
                                    data.message +
                                    "</div>" +
                                    "</div>" +
                                    "<img src='https://images.unsplash.com/photo-1590031905470-a1a1feacbb0b?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144' alt='My profile' class='w-6 h-6 rounded-full order-2'>" +
                                    "</div>" +
                                    "</div>" +
                                    "</li>")
                            }
                            if($(".send").length != r.length){
                                $("#chat").scrollTop(120);
                            }
                        }
                    })
                }, 780);

            }
        })
    })

})
