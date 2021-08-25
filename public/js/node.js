$("#chat").scrollTop($("#chat")[0].scrollHeight);
var auth_user = $("input[name='id']").val();
var socket = io.connect('http://localhost:3000', {query : {userId : auth_user},transports: ['websocket']});


socket.on('ConnectedUserArray', function (array){
    console.log(array)
    $.ajax({
      method: "GET",
      url : "/activeUsers",
      data : {
          ids: array
      }
    });
})

socket.on('activeUsers', (array) => {
    console.log(array.data)
    for(let i=0; i<=array.data.length-1; i++){
        if (array.data[i].id != auth_user){
            $(".users").append(`
         <li>
                    <a class="hover:bg-gray-100 border-b border-gray-300 px-3 py-2 cursor-pointer flex items-center text-sm focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                        <img class="h-10 w-10 rounded-full object-cover"
                             src="https://images.pexels.com/photos/837358/pexels-photo-837358.jpeg?auto=compress&cs=tinysrgb&h=750&w=1260"
                             alt="username" />
                        <div class="w-full pb-2">
                            <div class="flex justify-between">
                                <span class="block ml-2 font-semibold text-base text-gray-600 ">${array.data[i].name}</span>
                                <span class="block ml-2 text-sm text-gray-600">5 min ago</span>
                            </div>
                            <span class="block ml-2 text-sm text-gray-600">${array.data[i].message}</span>
                        </div>
                    </a>
                </li>
`)
        }
    }
})

socket.on('chat_message', function (data) {
    if (data['users_message'] == auth_user) {
        $("#sms").append(`
<li class="send ${data.message_id}">
    <div class="chat-message mt-3">
        <div class="text-gray-500 text-xs ml-11">${data.user}</div>
        <div class="flex items-end">
            <div class=" flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 ">
                <div class="div-del ">
                    <div class="group flex flex-row items-center">
                        <div class="flex flex-col">
                            <div class="attachment">
                                 ${(data.attachment ? `
                                    <div class="grid grid-cols-${data.attachment.length} message_${data.message_id}">
                                    </div>`: '')}
                            </div>
                            <div class="message">
                               ${(data.message ?
                                `<span class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">
                                    <span class="block">
                                            ${data.message}
                                    </span>
                                </span>`
                                : '')}
                            </div>
                        </div>
                        <div class="ml-2.5 text-red-500">
                            <div id="${data.message_id}"
                                 class="delete opacity-0 group-hover:opacity-100 transition-opacity delay-75">
                                <i class="fa fa-trash-o fa-lg"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <img
                src="https://images.unsplash.com/photo-1549078642-b2ba4bda0cdb?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144"
                alt="My profile" class="w-6 h-6 rounded-full order-1">
        </div>
    </div>
</li>
                `);

        if (data.attachment != null) {
            for ($i = 0; $i < data.attachment.length; $i++) {
                $(".message_" + data.message_id).append(`
                <div class="" style="height: 250px;">
                    <img src="img/${data.user}${data.attachment[$i]}" class="w-full h-full object-cover rounded-lg py-1 p-1" alt="">
                </div>
                `)
            }
        }

    } else {
        $("#sms").append(`
            <li class="send ${data.message_id}">
                <div class='chat-message mt-3'>
                    <div
                        class="text-gray-500 flex flex items-end justify-end mr-11 text-xs">${data.user}</div>
                    <div class='flex items-end justify-end'>
                        <div class='flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end '>
                            <div class="attachment_${data.message_id}">
                            </div>

                            ${(data.message ?
                                `<div class='px-4 py-2 rounded-lg inline-block rounded-br-none bg-blue-600 text-gray-100' >
                                    ${data.message}
                                </div>` :
                            `<div></div>`
                            )}

                        </div>
                        <img
                            src="https://images.unsplash.com/photo-1590031905470-a1a1feacbb0b?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144"
                            alt="My profile" class="w-6 h-6 rounded-full order-2">
                    </div>
                </div>
            </li>
        `);
        if (data.attachment != null ) {
            if ( data.attachment.length <= 4){
                $count = data.attachment.length
            }
            else{
                $count = 4;
            }
            $(".attachment_" + data.message_id).addClass(`grid grid-cols-${$count}`)

            for ($i = 0; $i < data.attachment.length; $i++) {
                $(".attachment_" + data.message_id).append(`
                <div class="" style="height: 250px;">
                    <img src="img/${data.user}${data.attachment[$i]}" class="w-full h-full object-cover rounded-lg py-1 p-1" alt="">
                </div>
                `)
            }
        }
    }
    $("#chat").scrollTop($("#chat")[0].scrollHeight);
})

socket.on('remove', function (data) {
    $("." + data.id).remove();
})

$(document).ready(function () {
    $("#send_keypress").keypress(function (e) {
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
                    data: {'_token': _token, 'message': message, 'user': user, 'id': id},
                    success: function (data) {
                        $("input[name='message']").val('');
                    }
                });
            }
        }
    })

    $("#send-message").click(function (e) {
        let formData = new FormData($('#form')[0]);
        $.ajax({
            url: '/sendMessage',
            data: formData,
            type: 'POST',
            contentType: false,
            processData: false,
            success: function (data) {
                $("input[name='message']").val('');
                $("#attachment").val('');
            }
        });
    })

    $("body").on('click', '.delete', function () {
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
    })

    $("#chat").scrollTop($("#chat")[0].scrollHeight);

    $('#chat').scroll(function () {
        if ($('#chat').scrollTop() == 0) {
            $.ajax({
                method: "GET",
                url: "/messages",
                headers: {
                    'Access-Control-Allow-Origin': '*'
                }
            }).done((r) => {
                setTimeout(function () {
                    var size = r['data'].length - $(".send").length;
                    for (i = size - 1; i >= size - 20; i--) {
                        if (r['data'].length == $(".send").length) {

                        } else if (r['data'][i].user_id == auth_user) {
                            $("#sms").prepend(`
                             <li class="send ${r['data'][i].id}">
                                <div class="chat-message mt-3">
                                    <div class="text-gray-500 text-xs ml-11">${r['data'][i].username}</div>
                                    <div class="flex items-end">
                                    <div class=" flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 ">
                                    <div class="div-del">
                                    <div class="group flex flex-row items-center">
                                        <span class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">
                                            ${r['data'][i].message}
                                        </span>
                                   <div  class="ml-2.5 text-red-500">
                                   <div id="${r['data'][i].id}" class="delete opacity-0 group-hover:opacity-100 transition-opacity delay-75"><i class="fa fa-trash-o fa-lg"></i></div>
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
                            $("#sms").prepend(`
                                <li class="send ${r['data'][i].id}">
                                    <div class='chat-message mt-3'>
                                        <div
                                            class="text-gray-500 flex flex items-end justify-end mr-11 text-xs">${r['data'][i].username}</div>
                                        <div class='flex items-end justify-end'>
                                            <div class='flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end'>
                                                <div
                                                    class='px-4 py-2 rounded-lg inline-block rounded-br-none bg-blue-600 text-gray-100'>
                                                    ${r['data'][i].message}
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
                    }
                }, 780);
            })
        }
    })
})


