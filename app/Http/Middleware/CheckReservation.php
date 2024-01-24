<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserAttendance;
use Symfony\Component\HttpFoundation\Response;

class CheckReservation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $enable_features = UserAttendance::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subHour())
            ->first();
        if(!$enable_features)
        {
            return finalResponse('failed',400,null,null,'You have not reserved any seat');
        }
        $request->merge(['restaurant_id'=>$enable_features->restaurant_id]);
        return $next($request);
    }
}
