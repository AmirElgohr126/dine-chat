<?php

namespace App\Http\Controllers\App\Nfc;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Models\UserAttendance;
use App\Http\Controllers\Controller;
use App\Http\Requests\Nfc\NfcRequest;
use Illuminate\Support\Facades\Validator;

class NfcController extends Controller
{
    public function VaildateNfcParameter(NfcRequest $request)
    {
        $data = $request->validated();
        try {
            // check card first ====================================================================================
            $restaurant = Restaurant::find($data['restaurant_id']);
            if(!$restaurant)
            {
                throw new \Exception(__('errors.invalid_parameter'), 405);
            }
            if (!($data['latitude'] == $restaurant->latitude && $data['longitude'] == $restaurant->longitude)) {
                throw new \Exception(__('errors.invalid_parameter'),405);
            }
            $nfcNumber = (int) $data['nfc_number'];
            $chair = $restaurant->chairs()->where('nfc_number', '=',$nfcNumber)->first();
            if (!$chair) {
                throw new \Exception(__('errors.invalid_parameter' . ' chair_number'),405);
            }
            // finsh card check ====================================================================================
            $checkReservationBefore = UserAttendance::where('user_id',$request->user()->id)
                ->where('created_at', '>', now()->subHour())
                ->first();
            if ($checkReservationBefore) {
                throw new \Exception(__('errors.you_make_reservation_in_another_place'), 405);
            }
            $conflictingReservation = UserAttendance::
                where('chair_id', $chair->id)
                ->where('restaurant_id',$restaurant->id)
                ->where('created_at', '>', now()->subHour())
                ->first();
            if ($conflictingReservation) {
                throw new \Exception(__('errors.this_place_is_reservstion_now'), 405);
            }
            // there is no reservation
            $reserve = UserAttendance::create([
                'user_id' => $request->user()->id,
                'chair_id' => $chair->id,
                'restaurant_id' => $restaurant->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            if ($reserve) {
                return finalResponse('success', 200,__('errors.success_reservation'));
            }
        } catch (\Exception $e) {
            return finalResponse('failed', $e->getCode(), null, null, $e->getMessage());
        }
    }
}
