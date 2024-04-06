<?php

namespace App\Service\Reservation;

use App\Events\DeleteReservation;
use App\Models\BookingDates;
use App\Models\HistoryAttendances;
use App\Models\UserAttendance;

use Illuminate\Http\Request;

class ReservationRestaurantService extends DistanceServices
{
    /**
     * create reservation
     * @param $restaurant
     * @param $request
     * @param $chair
     */
    public function createReservation($restaurant,$request,$chair) : mixed
    {
        $periodCheckReservation = BookingDates::firstRowRestaurant();
        $formattedDate = $periodCheckReservation->format('Y-m-d H:i:s');

        $reserve = UserAttendance::create([
            'user_id' => $request->user()->id,
            'chair_id' => $chair->id,
            'restaurant_id' => $restaurant->id,
            'created_at' => $formattedDate,
            'updated_at' => $formattedDate
        ]);
        return $reserve;
    }

    /**
     * check card parameters
     * @param $data
     * @param $restaurant
     * @throws /Exception
     * @return mixed
     */
    public function checkCard($restaurant, $data) : mixed
    {
        if (!$restaurant) {
            throw new \Exception(__('errors.invalid_parameter'), 405);
        }
        if (!($data['latitude'] == $restaurant->latitude && $data['longitude'] == $restaurant->longitude)) {
            throw new \Exception(__('errors.invalid_parameter'), 405);
        }
        $nfcNumber = (int) $data['nfc_number'];
        $chair = $restaurant->chairs()->where('nfc_number', '=', $nfcNumber)->first();
        if (!$chair) {
            throw new \Exception(__('errors.invalid_parameter' . ' chair_number'), 405);
        }
        return $chair;

    }

    /**
     * handle existing reservation
     * @param Request $request
     * @return void
     */
    public function handleExistingReservation(Request $request) : void
    {
        $checkReservationBefore = UserAttendance::where('user_id',$request->user()->id)
            ->where('created_at', '>=', now())
            ->first();
        if ($checkReservationBefore) {
            DeleteReservation::dispatch($checkReservationBefore->restaurant_id, $request->user()->id);
            $checkReservationBefore->delete();
        }
    }

    /**
     * handle conflicting reservation
     * @param $chair
     * @param $restaurant
     * @param $request
     */
    public function handleConflictingReservation($chair, $restaurant, $request) : void
    {
        $conflictingReservation = UserAttendance::where('chair_id', $chair->id)
            ->where('restaurant_id',$restaurant->id)
            ->where('created_at', '>=', now())
            ->first();
        if ($conflictingReservation) {
            DeleteReservation::dispatch($conflictingReservation->restaurant_id, $request->user()->id);
            $conflictingReservation->delete();
        }

    }


    /**
     * store history of user attendance
     * @param $restaurant
     * @param $request
     */
    public function storeHistory($restaurant, $request) : void
    {
        $userHistory = HistoryAttendances::where('restaurant_id', $restaurant->id)->where('user_id', $request->user()->id)->first();
        if(!$userHistory)
        {
            HistoryAttendances::create([
                'user_id' =>$request->user()->id,
                'restaurant_id' => $restaurant->id
            ]);
        }

    }


}
