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
    public function show(){
        return view('chat', ['message' => Message::all(),'current' => [Auth::user()]]);
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function sendMessage(Request $request)
    {
        $message = new Message();
        $message->username = $request->input('user');
        $message->message = $request->input('message');
        $message->save();
        $data = ['message' => $request->input('message'), 'user' => $request->input('user')];

       Redis::publish('channel', json_encode($data));

        return response()->json(['success' => true]);


    }
}
