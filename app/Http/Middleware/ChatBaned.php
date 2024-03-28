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

        $chat = Conversation::withTrashed()->find($request->id);

        // Ensure that the chat and its banChat relationship exist before accessing properties
        if ($chat && $chat->banChat && $chat->banChat->status == 'ban') {
            return finalResponse(403, 'You are banned from this chat');
        }

        // If the chat doesn't exist, you may want to handle it differently
        if (!$chat) {
            return finalResponse(404, 'Chat not found');
        }
        return $next($request);
    }
}
