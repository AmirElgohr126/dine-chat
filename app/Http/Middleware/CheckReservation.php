<?php

namespace App\Http\Middleware;

use App\Models\UserAttendancePublicPlace;
use Closure;
use Illuminate\Http\Request;
use App\Models\UserAttendance;
use Symfony\Component\HttpFoundation\Response;

class CheckReservation
{
    /**
     * Handle an incoming request.
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $enableFeaturesReservation = UserAttendance::where('user_id', $user->id)
            ->where('created_at', '>=', now())
            ->first();
        if (!$enableFeaturesReservation) {
            return finalResponse(false, __("errors.no_reservation"), 201);
        }

        if (isset($enableFeaturesReservation->restaurant_id)) {
           $request->merge(['restaurant_id' => $enableFeaturesReservation->restaurant_id,'type' => 'restaurant']);
        } else {
            $request->merge(['public_place_id' => $enableFeaturesReservation->public_place_id,'type' => 'public_place']);
        }
        return $next($request);
    }
}
