@section('content')

        <ul id="sms">
         {{--   @if($message)
                @if(count($message) > 20)
                    @for( $i = 19; $i>=0 ; $i--)
                        @if($message[$i]['user_id'] == Auth::user()->id)
                            <li class="send {{$message[$i]['id']}}">
                                <div class="chat-message mt-3">
                                    <div
                                        class="text-gray-500 text-xs ml-11">{{$message[$i]['username']}}</div>
                                    <div class="flex items-end">
                                        <div
                                            class=" flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 ">
                                            <div class="div-del ">
                                                <div class="group flex flex-row items-center">
                                                    <div class="flex flex-col">
                                                        <div class="attachment">
                                                            @if($message[$i]['attachments'])
                                                                @if(count($message[$i]['attachments'])<4)
                                                                    <div
                                                                        class="grid grid-cols-{{count($message[$i]['attachments'])}}">
                                                                        @for($index = 0; $index < count($message[$i]['attachments']); $index++ )
                                                                            <div class=""
                                                                                 style="height: 250px;">
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
                                                                            <div class=""
                                                                                 style="height: 250px;">
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
                                        <div
                                            class='flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end'>
                                            @if($message[$i]['attachments'])
                                                @if(count($message[$i]['attachments'])<4)
                                                    <div
                                                        class="grid grid-cols-{{count($message[$i]['attachments'])}}">
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
                                    <div
                                        class="text-gray-500 text-xs ml-11">{{$message[$j]['username']}}</div>
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
                                                                            <div class=""
                                                                                 style="height: 250px;">
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
                                                                            <div class=""
                                                                                 style="height: 250px;">
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
                                                                <span
                                                                    class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">
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
                                        <div
                                            class='flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end'>
                                            @if($message[$j]['attachments'])
                                                @if(count($message[$j]['attachments'])<4)
                                                    <div
                                                        class="grid grid-cols-{{count($message[$j]['attachments'])}}">
                                                        @for($index = 0; $index < count($message[$j]['attachments']); $index++ )
                                                            <div class="" style="height: 250px;">
                                                                <img
                                                                    src="{{asset('img/'.$message[$j]['username'].$message[$j]['attachments'][$index]['attachment'])}}"
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
            @endif--}}
        </ul>
    </div>
    <form id='form' action="sendMessage" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="border-t-2 border-gray-200 px-4 pt-4 mb-2 sm:mb-0">
            <div class="inputsForForm relative flex">

            </div>
        </div>
    </form>

@endsection
