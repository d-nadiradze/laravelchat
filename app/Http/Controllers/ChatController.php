<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttributeRequest;
use App\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Nette\Utils\Image;
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
        $arr = [];
        $attachments = [];
        $x = 100;

        if($request->attachment){
            $message = new Message();
            $message->username = $request->input('user');
            $message->user_id = $request->input('id');
            $message->message = $request->input('message');
            $message->save();

            foreach ($request->attachment as $image){
                $img_name = $message->id."_".$image->getClientOriginalName();
                $save_path = \public_path('img/'.$request->input('user'));
                if (!file_exists($save_path))
                {
                    mkdir($save_path, 777, true);
                }
                $img =\Image::make($image)->resize(null, 400, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(\public_path('img/'.$request->input('user').$img_name),$x);
            }

            foreach ($request->attachment as $item) {
                array_push($arr,$item);

                $attachment = new Attachment();
                $attachment->message_id = $message->id;
                $attachment->attachment = $message->id."_".$item->getClientOriginalName();
                $attachment->save();
                array_push($attachments,$message->id."_".$item->getClientOriginalName());
            }

            $data = [
                'event' => 'send',
                'message' => $message->message,
                'user' => $request->input('user'),
                'users_message' => $message->user_id,
                'attachment' =>  $attachments,
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
                'message_id' => $message->id
            ];
        }

        Redis::publish('channel', json_encode($data));
        return response()->json(['success' => true]);
    }


    public function fetchMessages(Request $request)
    {
        $data = [
            'data' => Message::all()->toArray(),
            'user' => Auth::user()->id,
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
