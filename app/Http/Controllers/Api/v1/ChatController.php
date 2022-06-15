<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\NewChatMessage;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
     public  function rooms(Request $request){
         return ChatRoom::all();
     }
     public  function  messages(Request $request, int  $roomId)  {
         return ChatMessage::where('chat_room_id', $roomId)->with('user')->orderByDesc('created_at')->get();
     }
     public  function  newMessage(Request $request, int $roomId ){
         $message = new ChatMessage();
         $message->chat_room_id = $roomId;
         $message->user_id = Auth::id();
         $message->message = $request->message;
         $message->save();
         broadcast(new NewChatMessage($message))->toOthers();

         return $message;
     }
}
