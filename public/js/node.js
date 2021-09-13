$("#chat").scrollTop($("#chat")[0].scrollHeight);
var auth_user = $("input[name='id']").val();
var socket = io.connect('http://localhost:3000', {query: {userId: auth_user}, transports: ['websocket']});

socket.on('ConnectedUserArray', function (array) {
    $.ajax({
        method: "GET",
        url: "/activeUsers",
        data: {
            ids: array
        }
    });
})
socket.on('activeUsers', (array) => {
    $(".users").empty();
    array.data.forEach((e) => {
        if ($(`li[userId=${e.id}]`).length === 0 && auth_user != e.id) {
            $(".users").append(`
         <li id='${e.id}' userId="${e.id}" class="active_user">
                    <a class="hover:bg-gray-100 border-b border-gray-300 px-3 py-2 cursor-pointer flex items-center text-sm focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                        <img class="h-10 w-10 rounded-full object-cover"
                             src="https://images.pexels.com/photos/837358/pexels-photo-837358.jpeg?auto=compress&cs=tinysrgb&h=750&w=1260"
                             alt="username" />
                        <div class="w-full pb-2">
                            <div class="flex justify-between">
                                <span class="block ml-2 font-semibold text-base text-gray-600 ">${e.name}</span>
                                <span class="block ml-2 text-sm text-gray-600"></span>
                            </div>
                            <span class="block ml-2 text-sm text-gray-600"></span>
                        </div>
                    </a>
                </li>
`)
        }
    })
})
socket.on('chat_message', function (data) {
    if (($('#user').val() == data.receiver_id && data.users_message == $('#receiver_id').val()) || data.users_message == auth_user) {
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
                                    </div>` : '')}
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
                                <i class="fa fa-trash-o fa-lg"></i>
                            </div>
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
                    <div class="text-gray-500 flex flex items-end justify-end mr-11 text-xs">${data.user}</div>
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
            if (data.attachment != null) {
                if (data.attachment.length <= 4) {
                    $count = data.attachment.length
                } else {
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
    }
})
socket.on('remove', function (data) {
    $("." + data).remove();
})
$(document).ready(function () {
    $("#chat").scrollTop($("#chat")[0].scrollHeight);
    /* responsive design */
    $("body").on('click', ".active_user", function (e){
        $('.chat-side').removeClass('hidden');
        $('.user-side').addClass('hidden');
    })
    $("body").on('click', "#active_user_button", function (e){
        $('.user-side').removeClass('hidden');
        $('.user-side').addClass('w-2/5');
        $('.user-side').removeClass('w-screen');
    })
    $("body").on('click', '#sms', function (){
        $('.user-side').addClass('hidden');
    })

    /* send message */
    $("body").on('keypress', "#send_keypress", function (e) {
        if (e.which == 13) {
            e.preventDefault();
            let _token = $("input[name='_token']").val();
            let user = $("input[name='user']").val();
            let id = $("input[name='id']").val();
            let message = $("input[name='message']").val();
            let receiver_id = $("input[name='receiver_id']").val();
            if (message != '') {
                $.ajax({
                    type: "POST",
                    url: '/sendMessage',
                    dataType: "json",
                    data: {'_token': _token, 'message': message, 'user': user, 'id': id, 'receiver_id': receiver_id},
                    success: function (data) {
                        $("input[name='message']").val('');
                    }
                });
            }
        }
    })
    $("body").on('click', '#send-message', function (e) {
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

    /* delete message */
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

    /* private chat */
    $("body").on('click', '.active_user', function () {
        $('#receiver_id').val(this.id);
        socket.emit('privateChat', this.id)
        $.ajax({
            type: "GET",
            url: '/privateChat',
            data: {'id': this.id},
            success: function (message) {
                $('.chat-header').empty()
                $('.inputsForForm').empty()
                $('#sms').empty()

                $('.inputsForForm').append(`
                <label class="mr-4 inline-flex items-center justify-center rounded-full h-12 w-12 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" class="h-6 w-6 text-gray-600">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                    <input type='file' id="attachment" name="attachment[]" multiple class="hidden"/>
                </label>
                <input type="text" id='send_keypress' name='message' placeholder="Write Something"
                       class="w-full focus:outline-none focus:placeholder-gray-400 text-gray-600 placeholder-gray-400 pl-12 bg-gray-100 hover:bg-blue-50 rounded-full py-3">
                <div class="absolute right-0 items-center inset-y-0 hidden sm:flex">
                    <button type="button" id="send-message"
                            class="inline-flex items-center justify-center rounded-full h-12 w-12 transition duration-500 ease-in-out text-white bg-blue-500 hover:bg-blue-400 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="h-6 w-6 transform rotate-90">
                            <path
                                d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z">
                            </path>
                        </svg>
                    </button>
                </div>`)
                $('.chat-header').prepend(`
                <div class="flex sm:items-center justify-between p-2 pb-4 border-b-2 border-gray-200" id="active_user_button">
                  <div class="px-2 py-1 bg-blue-600 rounded text-gray-50 cursor-pointer md:hidden block">
                        <i class="fa fa-group"></i>
                  </div>
                    <div class="flex justify-center items-center w-full space-x-4">
                        <img src="https://images.unsplash.com/photo-1549078642-b2ba4bda0cdb?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144" alt="" class="w-8 sm:w-10 h-8 sm:h-10 rounded-full">
                        <div class="flex flex-col leading-tight">
                            <div class="text-2xl mt-1 flex items-center">
                                <span class="text-gray-700 mr-3 text-base">${message[1].name}</span>
                                <span class="text-green-500">
                                  <svg width="10" height="10">
                                     <circle cx="5" cy="5" r="5" fill="currentColor"></circle>
                                  </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>`)
                message[0].forEach((data) => {
                    if (data.user_id == auth_user) {
                        $("#sms").append(`
<li class="send ${data.id}">
    <div class="chat-message mt-3">
        <div class="text-gray-500 text-xs ml-11">${data.username}</div>
        <div class="flex items-end">
            <div class=" flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 ">
                <div class="div-del ">
                    <div class="group flex flex-row items-center">
                        <div class="flex flex-col">
                            <div class="attachment">
                                 ${(data.attachments ? `
                                    <div class="grid grid-cols-${data.attachments.length} message_${data.id}">
                                    </div>` : '')}
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
                            <div id="${data.id}"
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
                        if (data.attachments != null) {
                            for ($i = 0; $i < data.attachments.length; $i++) {
                                $(".message_" + data.id).append(`
                <div class="" style="height: 250px;">
                    <img src="img/${data.username}${data.attachments[$i].attachment}" class="w-full h-full object-cover rounded-lg py-1 p-1" alt="">
                </div>
                `)
                            }
                        }
                    } else {
                        $("#sms").append(`
            <li class="send ${data.id}">
                <div class='chat-message mt-3'>
                    <div class="text-gray-500 flex flex items-end justify-end mr-11 text-xs">${data.username}</div>
                    <div class='flex items-end justify-end'>
                        <div class='flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end '>
                            <div class="attachment_${data.id}">
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
                        if (data.attachments != null) {
                            if (data.attachments.length <= 4) {
                                $count = data.attachments.length
                            } else {
                                $count = 4;
                            }
                            $(".attachment_" + data.id).addClass(`grid grid-cols-${$count}`)

                            for ($i = 0; $i < data.attachments.length; $i++) {
                                $(".attachment_" + data.id).append(`
                <div class="" style="height: 250px;">
                    <img src="img/${data.username}${data.attachments[$i].attachment}" class="w-full h-full object-cover rounded-lg py-1 p-1" alt="">
                </div>
                `)
                            }
                        }
                    }
                    $("#chat").scrollTop($("#chat")[0].scrollHeight);

                })

                message = null;
            }
        })
    })

    /* infinity scroll (sms loader) */
    $('#chat').scroll(function () {
        if ($('#chat').scrollTop() == 0) {
            $.ajax({
                method: "GET",
                url: "/messages",
                data: {'id' : $('#receiver_id').val()},
                headers: {
                    'Access-Control-Allow-Origin': '*'
                }
            }).done((r) => {
                setTimeout(function () {
                    var size = r.length - $(".send").length;
                    for (i = size - 1; i >= size - 20; i--) {
                        if (r.length == $(".send").length) {

                        } else if (r[i].user_id == auth_user) {
                            $("#sms").prepend(`
                             <li class="send ${r[i].id}">
                                <div class="chat-message mt-3">
                                    <div class="text-gray-500 text-xs ml-11">${r[i].username}</div>
                                        <div class="flex items-end">
                                        <div class=" flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 ">
                                            <div class="div-del">
                                                <div class="group flex flex-row items-center">
                                                <span class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">
                                                    ${r[i].message}
                                                </span>
                                                   <div  class="ml-2.5 text-red-500">
                                                        <div id="${r[i].id}" class="delete opacity-0 group-hover:opacity-100 transition-opacity delay-75"><i class="fa fa-trash-o fa-lg"></i></div>
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
                                <li class="send ${r[i].id}">
                                    <div class='chat-message mt-3'>
                                        <div
                                            class="text-gray-500 flex flex items-end justify-end mr-11 text-xs">${r[i].username}</div>
                                        <div class='flex items-end justify-end'>
                                            <div class='flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end'>
                                                <div
                                                    class='px-4 py-2 rounded-lg inline-block rounded-br-none bg-blue-600 text-gray-100'>
                                                    ${r[i].message}
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


