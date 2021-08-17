$("#chat").scrollTop($("#chat")[0].scrollHeight);

var socket = io.connect('http://localhost:3000', {transports: ['websocket']});


socket.on('remove', function (data) {
    $("." + data.id).remove();
})


socket.on('chat_message', function (data) {
    var auth_user = $("input[name='id']").val();
    console.log(auth_user)
    if (data['users_message'] == auth_user) {
        $("#sms").append(`
    <li class="send ${data.message_id}">
        <div class="chat-message mt-3">
            <div class="text-gray-500 text-xs ml-11">${data.user}</div>
            <div class="flex items-end">
            <div class=" flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 ">
            <div class="div-del">
            <div class="group flex flex-row items-center">
                <span class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">
                    ${data.message}
                </span>
           <div  class="ml-2.5 text-red-500">
           <div id="${data.message_id}" class="delete opacity-0 group-hover:opacity-100 transition-opacity delay-75"><i class="fa fa-trash-o fa-lg"></i></div>
           </div>
           </div>
           </div>
           </div>
            <img src="https://images.unsplash.com/photo-1549078642-b2ba4bda0cdb?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144"
                 alt="My profile" class="w-6 h-6 rounded-full order-1">
            </div>
            </div>
        </li>
                `);
    } else {
        $("#sms").append(`
    <li class="send ${data.message_id}">
                <div class='chat-message mt-3'>
                    <div
                        class="text-gray-500 flex flex items-end justify-end mr-11 text-xs">${data.user}</div>
                    <div class='flex items-end justify-end'>
                        <div class='flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end'>
                            <div
                                class='px-4 py-2 rounded-lg inline-block rounded-br-none bg-blue-600 text-gray-100'>
                                ${data.message}
                            </div>
                        </div>
                        <img
                            src="https://images.unsplash.com/photo-1590031905470-a1a1feacbb0b?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144"
                            alt="My profile" class="w-6 h-6 rounded-full order-2">
                    </div>
                </div>
            </li>
        `);

    }
    $(".delete").on('click', function () {
            var _token = $("input[name='_token']").val();
            var id = this.id;
            $.ajax({
                type: "POST",
                url: '/removeMessage',
                dataType: "json",
                data: {'_token': _token, 'id': id},
                success: function (data) {
                    console.log("removed")
                }
            })
        },
    );

    $("#chat").scrollTop($("#chat")[0].scrollHeight);
});

$(document).ready(function () {
    $("#s").keypress(function (e) {
        if (e.which == 13) {
            e.preventDefault();
            var _token = $("input[name='_token']").val();
            var user = $("input[name='user']").val();
            var id = $("input[name='id']").val();
            var message = $("input[name='message']").val();
            if (message != '') {
                $.ajax({
                    type: "POST",
                    url: '/sendMessage',
                    dataType: "json",
                    data: {'_token': _token, 'message': message, 'user': user,'id':id},
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
        var id = $("input[name='id']").val();
        var message = $("input[name='message']").val();
        if (message != '') {
            $.ajax({
                type: "POST",
                url: '/sendMessage',
                dataType: "json",
                data: {'_token': _token, 'message': message, 'user': user,'id':id},
                success: function (data) {
                    $("input[name='message']").val('');
                }
            });
        }
    })

    $(".delete").on('click', function () {

            var _token = $("input[name='_token']").val();
            var id = this.id;
            $.ajax({
                type: "POST",
                url: '/removeMessage',
                dataType: "json",
                data: {'_token': _token, 'id': id},
                success: function (data) {
                    console.log("removed")
                }
            })
        },
    );
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
        for (let i = 0; i < r.length; i++) {
            messagesLength += r[i].length;
        }
        $("#chat").scrollTop($("#chat")[0].scrollHeight);

        $('#chat').scroll(function () {
            if ($('#chat').scrollTop() == 0) {
                setTimeout(function () {
                    let currentLength = $(".send").length
                    if ((r['data'].length - currentLength) < 20) {
                        var size = r['data'].length - currentLength;
                    } else {
                        var size = 20;
                    }
                    var currentArray = []
                    for (let i = currentLength; i < currentLength + size; i++) {
                        currentArray.push(r['data'][i]);
                    }
                    currentArray.forEach((data, j) => {
                        if (messagesLength == $('.send').length) {
                        } else {
                            if (data.user_id == r['user']){
                                $("#sms").prepend(`
     <li class="send ${data.id}">
        <div class="chat-message mt-3">
            <div class="text-gray-500 text-xs ml-11">${data.username}</div>
            <div class="flex items-end">
            <div class=" flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 ">
            <div class="div-del">
            <div class="group flex flex-row items-center">
                <span class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">
                    ${data.message}
                </span>
           <div  class="ml-2.5 text-red-500">
           <div id="${data.id}" class="delete opacity-0 group-hover:opacity-100 transition-opacity delay-75"><i class="fa fa-trash-o fa-lg"></i></div>
           </div>
           </div>
           </div>
           </div>
            <img src="https://images.unsplash.com/photo-1549078642-b2ba4bda0cdb?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144"
                 alt="My profile" class="w-6 h-6 rounded-full order-1">
            </div>
            </div>
        </li>`)
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
                            if ($(".send").length != r.length) {
                                $("#chat").scrollTop(120);
                            }
                        }
                    })
                }, 780);

            }
        })
    })

})
