<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttributeRequest;
use App\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
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
        $this->message = array_reverse($message::with('attachments')->get()->toArray());
    }

    public function show()
    {
        return view('chat', ['message' => $this->message, 'current' => [Auth::user()], 'attachments' => Attachment::all()->toArray()]);
    }

    public function sendMessage(Request $request)
    {

        if($request->attachment){
            $img_name = $request->input('user')."_".$request->attachment[0]->getClientOriginalName();
            $request->attachment[0]->move(public_path('img'),$img_name);

            $message = new Message();
            $message->username = $request->input('user');
            $message->user_id = $request->input('id');
            $message->message = $request->input('message');
            $message->save();

            $attachment = new Attachment();
            $attachment->message_id = $message->id;
            $attachment->attachment = $img_name;
            $attachment->save();

            $data = [
                'event' => 'send',
                'message' => $message->message,
                'user' => $request->input('user'),
                'users_message' => $message->user_id,
                'message_id' => $message->id
            ];
        }

        else if ($request->message) {
            $message = new Message();
            $message->username = $request->input('user');
            $message->user_id = $request->input('id');
            $message->message = $request->input('message');
            $message->save();

            $data = [
                'event' => 'send',
                'message' => $request->input('message'),
                'user' => $request->input('user'),
                'users_message' => $message->user_id,
                'attachment' => $request->attachment,
                'message_id' => $message->id
            ];
        }

        Redis::publish('channel', json_encode($data));
        return response()->json(['success' => true]);
    }


    public function fetchMessages()
    {
        $data = [
            'data' => Message::all()->toArray(),
            'user' => Auth::user()->id
        ];

        return $data;
    }

    public function remove(Request $request)
    {
        $data = ['event' => 'remove', 'id' => $request->id];
        Redis::publish('channel', json_encode($data));
        Message::find($request->id)->delete();
        return response()->json(['success' => true]);
    }

}
