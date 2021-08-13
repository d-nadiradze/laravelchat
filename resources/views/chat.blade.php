<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chat</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.1.2/socket.io.js"></script>

</head>
<body>
<style>
    .scrollbar-w-2::-webkit-scrollbar {
        width: 0.25rem;
        height: 0.25rem;
    }

    .scrollbar-track-blue-lighter::-webkit-scrollbar-track {
        --bg-opacity: 1;
        background-color: #f7fafc;
        background-color: rgba(247, 250, 252, var(--bg-opacity));
    }

    .scrollbar-thumb-blue::-webkit-scrollbar-thumb {
        --bg-opacity: 1;
        background-color: #edf2f7;
        background-color: rgba(237, 242, 247, var(--bg-opacity));
    }

    .scrollbar-thumb-rounded::-webkit-scrollbar-thumb {
        border-radius: 0.25rem;
    }
</style>

<div class="flex-1 p:2 sm:p-6 justify-between flex flex-col h-screen">
    <div id="chat"
         class="flex flex-col space-y-4 p-3 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">

        <ul id="sms">
            @if($message)

                @foreach( $message[0] as $messages)
                    @if($messages['username']== Auth::user()->name)
                        <li class="send">
                            <div class="chat-message mt-3">
                                <div class="text-gray-500 text-xs ml-11">{{$messages['username']}}</div>
                                <div class="flex items-end">
                                    <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 items-start">
                                        <div><span
                                                class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">{{$messages['message']}}</span>
                                        </div>
                                    </div>
                                    <img
                                        src="https://images.unsplash.com/photo-1549078642-b2ba4bda0cdb?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144"
                                        alt="My profile" class="w-6 h-6 rounded-full order-1">
                                </div>
                            </div>
                        </li>
                    @else
                        <li class="send">
                            <div class='chat-message mt-3'>
                                <div
                                    class="text-gray-500 flex flex items-end justify-end mr-11 text-xs">{{$messages['username']}}</div>
                                <div class='flex items-end justify-end'>
                                    <div class='flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end'>
                                        <div
                                            class='px-4 py-2 rounded-lg inline-block rounded-br-none bg-blue-600 text-gray-100'>
                                            {{$messages['message']}}
                                        </div>
                                    </div>
                                    <img
                                        src="https://images.unsplash.com/photo-1590031905470-a1a1feacbb0b?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144"
                                        alt="My profile" class="w-6 h-6 rounded-full order-2">
                                </div>
                            </div>
                        </li>
                    @endif
                @endforeach

            @else
                <li class="sms"></li>
            @endif


        </ul>

    </div>
    <form id='form' action="sendmessage" method="POST">
        @csrf
        <input type="hidden" name="user" value="{{ Auth::user()->name }}">
        <div class="border-t-2 border-gray-200 px-4 pt-4 mb-2 sm:mb-0">
            <div class="relative flex">

                <input type="text" id='s' name='message' placeholder="Write Something"
                       class="w-full focus:outline-none focus:placeholder-gray-400 text-gray-600 placeholder-gray-600 pl-12 bg-gray-200 rounded-full py-3">

                <div class="absolute right-0 items-center inset-y-0 hidden sm:flex">
                    <button type="button" id="send-message"
                            class="inline-flex items-center justify-center rounded-full h-12 w-12 transition duration-500 ease-in-out text-white bg-blue-500 hover:bg-blue-400 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="h-6 w-6 transform rotate-90">
                            <path
                                d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                        </svg>
                    </button>
                </div>

            </div>
        </div>
    </form>

</div>

<script>


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

                    setTimeout(function () {
                        for( let i=r.length-2; i>=0; i--) {
                            r[i].forEach((data, j) => {
                                console.log()
                                if ($('#chat').scrollTop() == 0) {
                                    if (messagesLength == $('.send').length) {
                                        console.log('test')
                                    } else {
                                        if (data.username == current) {
                                            $("#sms").prepend( "<li class='send'>"+
                                                "<div class='chat-message mt-3'>" +
                                                "<div class='text-gray-500 text-xs ml-11'>" + data.user + "</div>" +
                                                "<div class='flex items-end'>" +
                                                "<div class='flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 items-start'>" +
                                                "<div>" + "<span class='px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600'>" + data.message + "</span>" + "</div>" +
                                                "</div>" +
                                                "<img src='https://images.unsplash.com/photo-1549078642-b2ba4bda0cdb?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144' alt='My profile' class='w-6 h-6 rounded-full order-1'>" +
                                                "</div>" +
                                                "</div>"+
                                                "</li>")
                                        } else {
                                            $(".sms").prepend(  "<li class='send'>"+
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
                                                "</li>")
                                        }

                                    }
                                }
                            })
                        }
                    },780);
            })
        })

    })//
</script>

</body>
</html>



