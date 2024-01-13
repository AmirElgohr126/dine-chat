<?php

namespace App\Http\Controllers\App\Nfc;

use App\Models\Restaurant;
use App\Models\BookingDates;
use App\Events\UpdateUserHall;
use App\Models\UserAttendance;
use App\Http\Controllers\Controller;
use App\Http\Requests\Nfc\NfcRequest;

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
                UpdateUserHall::dispatch($checkReservationBefore, $restaurantId);
                $checkReservationBefore->delete();
            }
            $conflictingReservation = UserAttendance::
                where('chair_id', $chair->id)
                ->where('restaurant_id',$restaurant->id)
                ->where('created_at', '>=', now())
                ->first();
            if ($conflictingReservation) {
                $restaurantId = $conflictingReservation->restaurant_id;
                UpdateUserHall::dispatch($conflictingReservation, $conflictingReservation->restaurant_id);
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

            UpdateUserHall::dispatch($reserve, $reserve->restaurant_id);
            if ($reserve) {
                return finalResponse('success', 200,__('errors.success_reservation'));
            }
        } catch (\Exception $e) {
            return finalResponse('failed', 500, null, null, $e->getMessage());
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
