<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttributeRequest;
use App\Models\Attachment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Nette\Utils\Image;
use PRedis;
use App\Http\Requests;
use App\Models\Message;
use App\Http\Controllers\Controller;
use Psy\Util\Json;


class ChatController extends Controller
{

    public function __construct(Message $message)
    {
        $this->middleware('auth');
        $this->message = $message::with('attachments')->get()->toArray();
    }

    public function show(Request $request)
    {
        $receiver = $request->input('receiver_id');
        return view('chat', ['receiver' => $receiver]);
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
            $message->get_by = $request->input('receiver_id');
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
                'receiver_id' => $request->input('receiver_id'),
                'users_message' => $message->user_id,
                'attachment' =>  $attachments,
                'message_id' => $message->id
            ];
        }
        else if ($request->message && $request->receiver_id != null) {
            $message = new Message();
            $message->username = $request->input('user');
            $message->user_id = $request->input('id');
            $message->get_By = $request->input('receiver_id');
            $message->message = $request->input('message');
            $message->save();
            $data = [
                'event' => 'send',
                'message' => $request->input('message'),
                'user' => $request->input('user'),
                'receiver_id' => $request->input('receiver_id'),
                'users_message' => $message->user_id,
                'message_id' => $message->id
            ];
        }
        Redis::publish('channel', json_encode($data));
        return response()->json(['success' => true]);
    }

    public function fetchMessages(Request $request)
    {
        $id = $request->id;

        $data = Message::where(function($query) use($id) {
            $query->where('user_id',$id)
                ->where('get_by',Auth::user()->id);

        })->orWhere(function($query) use($id) {
            $query->where('user_id', Auth::user()->id)
                ->where('get_by', $id);

        })->get()->toArray();

        return $data;
    }

    public function remove(Request $request)
    {
        $data = ['event' => 'remove', 'id' => $request->id];
        Message::find($request->id)->delete();
        Redis::publish('channel',json_encode($data));
        return response()->json(['success' => true]);

    }

    public function activeUsers(Request $request){
        $users = User::find($request->ids);
        $data = ['event' => 'activeUsers', 'data' => $users];
        Redis::publish('channel',json_encode($data));
        return view('chat',['receiver' => $request->id]);
    }

    public function privateChat(Request $request)
    {
        $id = $request->id;

        $username = User::find($id);
        $data = Message::where(function($query) use($id) {
            $query->where('user_id',$id)
                ->where('get_by',Auth::user()->id);

        })->orWhere(function($query) use($id) {
            $query->where('user_id', Auth::user()->id)
                ->where('get_by', $id);

        })->with('attachments')->orderBy('id','desc')->get()->take(20)->reverse()->values();

        return [$data,$username];
    }

}
