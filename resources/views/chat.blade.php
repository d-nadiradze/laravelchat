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
<div class="w-screen h-screen">
    <div class="grid grid-cols-4 min-w-full border rounded h-screen" style="min-height: 80vh;">
        <div class="col-span-1 bg-white border-r border-gray-300">
            <div class="my-3 mx-3 ">
                <div class="relative text-gray-600 focus-within:text-gray-400">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6 text-gray-500"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                    <input  placeholder="search"
                           class="py-2 pl-10 block w-full rounded bg-gray-100 outline-none focus:text-gray-700" type="search" name="search" required autocomplete="search" />
                </div>
            </div>

        <ul class="overflow-auto users" style="height: 500px;">
            <h2 class="ml-2 mb-2 text-gray-600 text-lg my-2">Chats</h2>

        </ul>

        </div>
        <div  class="col-span-3 bg-white overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">
            <div  class="flex-1 p:2 sm:p-6 justify-between flex flex-col h-full">
                <div id="chat"
                     class="flex flex-col space-y-4 p-3 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">
                    <ul id="sms">
                        @if($message)
                            @if(count($message)>20)
                                @for( $i=19; $i>=0 ; $i--)
                                    @if($message[$i]['user_id']== Auth::user()->id)
                                        <li class="send {{$message[$i]['id']}}">
                                            <div class="chat-message mt-3">
                                                <div class="text-gray-500 text-xs ml-11">{{$message[$i]['username']}}</div>
                                                <div class="flex items-end">
                                                    <div class=" flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 ">
                                                        <div class="div-del ">
                                                            <div class="group flex flex-row items-center">
                                                                <div class="flex flex-col">
                                                                    <div class="attachment">
                                                                        @if($message[$i]['attachments'])
                                                                            @if(count($message[$i]['attachments'])<4)
                                                                                <div
                                                                                    class="grid grid-cols-{{count($message[$i]['attachments'])}}">
                                                                                    @for($index = 0; $index < count($message[$i]['attachments']); $index++ )
                                                                                        <div class="" style="height: 250px;">
                                                                                            <img
                                                                                                src="{{asset('img/'.Auth::user()->name.$message[$i]['attachments'][$index]['attachment'])}}"
                                                                                                class="w-full h-full object-cover rounded-lg py-1 p-1"
                                                                                                alt="">
                                                                                        </div>
                                                                                    @endfor
                                                                                </div>
                                                                            @else
                                                                                <div class="grid grid-cols-4">
                                                                                    @for($index = 0; $index < count($message[$i]['attachments']); $index++ )
                                                                                        <div class="" style="height: 250px;">
                                                                                            <img
                                                                                                src="{{asset('img/'.Auth::user()->name.$message[$i]['attachments'][$index]['attachment'])}}"
                                                                                                class="w-full h-full object-cover rounded-lg py-1 p-1"
                                                                                                alt="">
                                                                                        </div>
                                                                                    @endfor
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                    <div class="message">
                                                                        @if($message[$i]['message'])
                                                                            <span
                                                                                class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">
                                                                <span class="block">
                                                                     {{$message[$i]['message']}}
                                                                </span>
                                                                </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="ml-2.5 text-red-500">
                                                                    <div id="{{$message[$i]['id']}}"
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
                                    @else
                                        <li class="send {{$message[$i]['id']}}">
                                            <div class='chat-message mt-3'>
                                                <div
                                                    class="text-gray-500 flex flex items-end justify-end mr-11 text-xs">{{$message[$i]['username']}}</div>
                                                <div class='flex items-end justify-end'>
                                                    <div class='flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end'>
                                                        @if($message[$i]['attachments'])
                                                            @if(count($message[$i]['attachments'])<4)
                                                                <div class="grid grid-cols-{{count($message[$i]['attachments'])}}">
                                                                    @for($index = 0; $index < count($message[$i]['attachments']); $index++ )
                                                                        <div class="" style="height: 250px;">
                                                                            <img
                                                                                src="{{asset('img/'.$message[$i]['username'].$message[$i]['attachments'][$index]['attachment'])}}"
                                                                                class="w-full h-full object-cover rounded-lg py-1 p-1"
                                                                                alt="">
                                                                        </div>
                                                                    @endfor
                                                                </div>
                                                            @else
                                                                <div class="grid grid-cols-4">
                                                                    @for($index = 0; $index < count($message[$i]['attachments']); $index++ )
                                                                        <div class="" style="height: 250px;">
                                                                            <img
                                                                                src="{{asset('img/'.$message[$i]['username'].$message[$i]['attachments'][$index]['attachment'])}}"
                                                                                class="w-full h-full object-cover rounded-lg py-1 p-1"
                                                                                alt="">
                                                                        </div>
                                                                    @endfor
                                                                </div>
                                                            @endif
                                                        @endif
                                                        @if($message[$i]['message'])
                                                            <span
                                                                class=' px-4 py-2 rounded-lg inline-block rounded-br-none bg-blue-600 text-gray-100'>
                                                {{$message[$i]['message']}}
                                            </span>
                                                        @endif
                                                    </div>
                                                    <img
                                                        src="https://images.unsplash.com/photo-1590031905470-a1a1feacbb0b?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144"
                                                        alt="My profile" class="w-6 h-6 rounded-full order-2">
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                @endfor
                            @else
                                @for( $j=count($message)-1; $j>=0 ; $j--)
                                    @if($message[$j]['user_id']== Auth::user()->id)
                                        <li class="send {{$message[$j]['id']}}">
                                            <div class="chat-message mt-3">
                                                <div class="text-gray-500 text-xs ml-11">{{$message[$j]['username']}}</div>
                                                <div class="flex items-end">
                                                    <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 ">
                                                        <div class="div-del ">
                                                            <div class="group flex flex-row items-center">
                                                                <div class="flex flex-col">
                                                                    <div class="attachment">
                                                                        @if($message[$j]['attachments'])
                                                                            @if(count($message[$j]['attachments'])<4)
                                                                                <div
                                                                                    class="grid grid-cols-{{count($message[$j]['attachments'])}}">
                                                                                    @for($index = 0; $index < count($message[$j]['attachments']); $index++ )
                                                                                        <div class="" style="height: 250px;">
                                                                                            <img
                                                                                                src="{{asset('img/'.Auth::user()->name.$message[$j]['attachments'][$index]['attachment'])}}"
                                                                                                class="w-full h-full object-cover rounded-lg py-1 p-1"
                                                                                                alt="">
                                                                                        </div>
                                                                                    @endfor
                                                                                </div>
                                                                            @else
                                                                                <div class="grid grid-cols-4">
                                                                                    @for($index = 0; $index < count($message[$j]['attachments']); $index++ )
                                                                                        <div class="" style="height: 250px;">
                                                                                            <img
                                                                                                src="{{asset('img/'.Auth::user()->name.$message[$j]['attachments'][$index]['attachment'])}}"
                                                                                                class="w-full h-full object-cover rounded-lg py-1 p-1"
                                                                                                alt="">
                                                                                        </div>
                                                                                    @endfor
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                    <div class="message">
                                                                        @if($message[$j]['message'])
                                                                            <span class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">
                                                                    <span class="block">
                                                                         {{$message[$j]['message']}}
                                                                    </span>
                                                                </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="ml-2.5 text-red-500">
                                                                    <div id="{{$message[$j]['id']}}"
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
                                    @else
                                        <li class="send {{$message[$j]['id']}}">
                                            <div class='chat-message mt-3'>
                                                <div
                                                    class="text-gray-500 flex flex items-end justify-end mr-11 text-xs">{{$message[$j]['username']}}</div>
                                                <div class='flex items-end justify-end'>
                                                    <div class='flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end'>
                                                        @if($message[$j]['attachments'])
                                                            @if(count($message[$j]['attachments'])<4)
                                                                <div class="grid grid-cols-{{count($message[$j]['attachments'])}}">
                                                                    @for($index = 0; $index < count($message[$j]['attachments']); $index++ )
                                                                        <div class="" style="height: 250px;">
                                                                            <img src="{{asset('img/'.$message[$j]['username'].$message[$j]['attachments'][$index]['attachment'])}}"
                                                                                 class="w-full h-full object-cover rounded-lg py-1 p-1"
                                                                                 alt="">
                                                                        </div>
                                                                    @endfor
                                                                </div>
                                                            @else
                                                                <div class="grid grid-cols-4">
                                                                    @for($index = 0; $index < count($message[$j]['attachments']); $index++ )
                                                                        <div class="" style="height: 250px;">
                                                                            <img
                                                                                src="{{asset('img/'.$message[$j]['username'].$message[$j]['attachments'][$index]['attachment'])}}"
                                                                                class="w-full h-full object-cover rounded-lg py-1 p-1"
                                                                                alt="">
                                                                        </div>
                                                                    @endfor
                                                                </div>
                                                            @endif
                                                        @endif
                                                        @if($message[$j]['message'])
                                                            <span
                                                                class=' px-4 py-2 rounded-lg inline-block rounded-br-none bg-blue-600 text-gray-100'>
                                                {{$message[$j]['message']}}
                                            </span>
                                                        @endif
                                                    </div>
                                                    <img
                                                        src="https://images.unsplash.com/photo-1590031905470-a1a1feacbb0b?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144"
                                                        alt="My profile" class="w-6 h-6 rounded-full order-2">
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                @endfor
                            @endif
                        @else
                            <li>no messages</li>
                        @endif
                    </ul>

                </div>
                <form id='form' action="sendMessage" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user" value="{{ Auth::user()->name }}">
                    <input type="hidden" name="id" id="user" value="{{ Auth::user()->id }}">
                    <div class="border-t-2 border-gray-200 px-4 pt-4 mb-2 sm:mb-0">
                        <div class="relative flex">
                            <label class="mr-4 inline-flex items-center justify-center rounded-full h-12 w-12 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6 text-gray-600">--}}
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                <input type='file' id="attachment" name="attachment[]" multiple class="hidden" />
                            </label>
                            <input type="text" id='send_keypress' name='message' placeholder="Write Something"
                                   class="w-full focus:outline-none focus:placeholder-gray-400 text-gray-600 placeholder-gray-400 pl-12 bg-gray-100 hover:bg-blue-50 rounded-full py-3">
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
        </div>
    </div>
</div>


<script src="js/node.js"></script>

</body>
</html>



