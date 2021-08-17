<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use PRedis;
use App\Http\Requests;
use App\Models\Message;
use App\Http\Controllers\Controller;


class ChatController extends Controller
{




    public function __construct(Message $message)
    {
        $this->middleware('auth');
        $this->message = array_reverse($message::all()->toArray());
    }

    public function show()
    {
    return view('chat', ['message' => $this->message, 'current' => [Auth::user()]]);
    }

    public function sendMessage(Request $request)
    {
        $message = new Message();
        $message->username = $request->input('user');
        $message->user_id = $request->input('id');
        $message->message = $request->input('message');
        $message->save();

        $data = [
            'event'=> 'send',
            'message' => $request->input('message'),
            'user' => $request->input('user'),
            'users_message'=> $message->user_id,
            'message_id' => $message->id
        ];

        Redis::publish('channel', json_encode($data));
        return response()->json(['success' => true]);
    }

    public function fetchMessages()
    {
        $data =  [
            'data' => $this->message,
            'user' => Auth::user()->id
        ];

        return $data;

    }
    public function remove(Request $request)
    {
        $data = ['event' => 'remove' , 'id'=>$request->id];
        Redis::publish('channel', json_encode($data));
        Message::find($request->id)->delete();
        return response()->json(['success' => true]);
    }
}
