<?php

namespace App\Http\Controllers\V2\App\Nfc;

use App\Models\Restaurant;
use App\Models\BookingDates;
use App\Events\UpdateUserHall;
use App\Models\UserAttendance;
use App\Events\DeleteReservation;
use App\Models\HistoryAttendances;
use App\Http\Controllers\Controller;
use App\Http\Requests\V2\App\Nfc\NfcRequest;


class NfcController extends Controller
{
    public function VaildateNfcParameter(NfcRequest $request)
    {
        $data = $request->validated();
        try {
            $restaurant = Restaurant::find($data['restaurant_id']);
            $chair = $this->checkCard($restaurant,$data);
            // ================================== finsh card check ==================================

            $checkReservationBefore = UserAttendance::where('user_id',$request->user()->id)
                ->where('created_at', '>=', now())
                ->first();
            // ----------------------------------------------------------------------------
            if ($checkReservationBefore) {
                $restaurantId = $checkReservationBefore->restaurant_id ;
                DeleteReservation::dispatch($restaurantId, $request->user()->id);
                $checkReservationBefore->delete();
            }

            $conflictingReservation = UserAttendance::
                where('chair_id', $chair->id)
                ->where('restaurant_id',$restaurant->id)
                ->where('created_at', '>=', now())
                ->first();
            if ($conflictingReservation) {
                $restaurantId = $conflictingReservation->restaurant_id;
                DeleteReservation::dispatch($restaurantId, $request->user()->id);
                $conflictingReservation->delete();
            }

            // there is no reservation
            $periodCheckReservation = BookingDates::firstRow();
            $formattedDate = $periodCheckReservation->format('Y-m-d H:i:s');
            $reserve = UserAttendance::create([
                'user_id' => $request->user()->id,
                'chair_id' => $chair->id,
                'restaurant_id' => $restaurant->id,
                'created_at' => $formattedDate,
                'updated_at' => $formattedDate
            ]);

            // store history on HistoryAttendance
            $userHistory = HistoryAttendances::where('restaurant_id', $restaurant->id)->where('user_id', $request->user()->id)->first();
            if(!$userHistory)
            {
                HistoryAttendances::create([
                    'user_id' =>$request->user()->id,
                    'restaurant_id' => $restaurant->id
                ]);
            }

            // $event = new UpdateUserHall($reserve, $reserve->restaurant_id);
            //$response = sendEvent('restaurant1', 'UpdateUserHall', $event->broadcastWith(),$request->bearerToken());
            UpdateUserHall::dispatch($reserve, $reserve->restaurant_id);
            if ($reserve) {
                return finalResponse('success', 200,$reserve);
            }
        } catch (\Exception $e) {
            return finalResponse('failed', 400, null, null, $e->getMessage());
        }
    }


    public function checkCard($restaurant,$data)
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
}
