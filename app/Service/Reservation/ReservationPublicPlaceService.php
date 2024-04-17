<?php

namespace App\Service\Reservation;

use App\Models\BookingDates;
use App\Models\PublicPlace;
use App\Models\UserAttendancePublicPlace;

/****************************************************
 * ReservationPublicPlaceService
 ****************************************************
 * Handle the reservation of public places
 *
 * @package App\Service\Reservation
 ****************************************************/
class ReservationPublicPlaceService extends DistanceServices
{

    /**
     * check card parameters
     * @throws /Exception
     */
    public function checkCard($data) : PublicPlace
    {
        $publicPlace = PublicPlace::find($data['public_place_id']);
        if (!($data['latitude'] == $publicPlace->latitude && $data['longitude'] == $publicPlace->longitude)) {
            throw new \Exception(__('errors.invalid_parameter'), 405);
        }
        return $publicPlace;

    }



    /**
     * handle existing reservation
     * @param $publicPlace
     * @param $data
     * @return void
     */
    public function handleExistingReservation($publicPlace,$user) : void
    {
        $checkReservationBefore = UserAttendancePublicPlace::where('user_id', $user->id)
            ->where('public_place_id', $publicPlace->id)
            ->where('created_at', '>=', now())
            ->first();
        if ($checkReservationBefore) {
            $checkReservationBefore->delete();
        }

    }






    /**
     * create reservation
     * @param $publicPlace
     * @param $user
     * @return mixed
     */
    public function createReservation($publicPlace,$user) : mixed
    {
        $periodReservation = BookingDates::firstRowForPublicPlaces();
        $reserve = UserAttendancePublicPlace::create([
            'user_id' => $user->id,
            'public_place_id' => $publicPlace->id,
            'created_at' => $periodReservation,
            'updated_at' => $periodReservation
        ]);
        return $reserve;
    }



}
