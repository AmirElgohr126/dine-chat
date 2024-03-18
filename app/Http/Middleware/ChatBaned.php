<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChatBaned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // public function scopeBan(Builder $query, $conversationId)
        // {
        //     return $query->where('conversation_id',$conversationId)->where('ban',1);
        // }
        $chat = Conversation::find($request->id);
        if($chat->banChat->status == 'ban'){
            return response()->json(['message' => 'Chat is baned'], 403);
        }
        return $next($request);
    }
}
