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
        $chat = Conversation::find($request->id);
        if ($chat->ban) {
            return finalResponse('failed', 403, null, 'chat is baned');
        }
        return $next($request);
    }
}
