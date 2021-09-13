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
    <link rel="stylesheet" href="{{ asset('css/scroll.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.1.2/socket.io.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


</head>
<body>
<div class=" w-screen h-screen">
    <div class="flex flex-row min-w-full border rounded h-full" style="min-height: 80vh;">
        <div class="user-side z-10 absolute md:relative h-full w-full md:w-1/5  md:block bg-gray-200 md:bg-white border-r border-gray-300">
            <div class="my-3 mx-3 ">
                <div class="relative text-gray-600 focus-within:text-gray-400">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                        <svg fill="none" stroke="currentColor"
                             stroke-linecap="round" stroke-linejoin="round"
                             stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6 text-gray-500">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input placeholder="search"
                           class="py-2 pl-10 block w-full rounded bg-gray-100 outline-none focus:text-gray-700"
                           type="search" name="search" required autocomplete="search"/>
                </div>
            </div>
            <ul class="overflow-auto users" style="height: 500px;">
                <h2 class="ml-2 mb-2 text-gray-600 text-lg my-2">Chats</h2>
            </ul>
        </div>
        <div class="chat-side w-screen md:w-4/5   bg-white overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">
            <div class="flex-1 p:2 md:p-6 justify-between flex flex-col h-full">
                <div class="chat-header">

                </div>
                <div id="chat"
                     class="flex flex-col space-y-4 p-3 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">
                    <ul id="sms">
                    </ul>
                </div>
                <form id='form' action="sendMessage" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user" value="{{ Auth::user()->name }}">
                    <input type="hidden" name="id" id="user" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="receiver_id" id="receiver_id">
                    <div class="border-t-2 border-gray-200 px-4 pt-4 mb-2 sm:mb-0">
                        <div class="inputsForForm relative flex">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="js/node.js"></script>

</body>
</html>
