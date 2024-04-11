<?php

namespace App\Http\Controllers\V1\App\PublicPlaces;

use App\Http\Controllers\Controller;
use App\Models\UserAttendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicPlaceController extends  Controller
{

    /**
     *  delete reservation for public place
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteReservation(Request $request) : JsonResponse
    {
        $request->validate([
            'public_place_id' => 'required|integer|exists:public_places,id',
        ]);
        $user = auth()->user();
        $reservation = UserAttendance::where('user_id', $user->id)
            ->where('public_place_id', $request->public_place_id)
            ->where('created_at', '>', now())
            ->first();
        if ($reservation) {
            $reservation->delete();
            // dispatch event to delete the reservation
            return finalResponse('success', 200);
        } else {
            return finalResponse('reservation not found', 404);
        }
    }



    /**
     * get all users public places
     * @param Request $request
     * @return JsonResponse
     */
    public function usersInPublicPlace(Request $request): JsonResponse
    {
        $request->validate([
            'public_place_id' => 'required|integer|exists:public_places,id',
        ]);
        $users = UserAttendance::where('public_place_id', $request->public_place_id)
            ->where('created_at', '>', now())
            ->get();
        return finalResponse('success', 200, $users);
    }
}
