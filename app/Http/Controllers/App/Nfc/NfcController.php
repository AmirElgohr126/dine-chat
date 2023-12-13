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
            $restaurant = Restaurant::findOrFail($data['restaurant_id']);
            if (!($data['latitude'] == $restaurant->latitude && $data['longitude'] == $restaurant->longitude)) {
                throw new \Exception(__('errors.invalid_parameter'));
            }
            $table = $restaurant->tables()->where('table_number', $data['table_number'])->firstOrFail();
            $chair = $table->chairs()->where('chair_number', $data['chair_number'])->firstOrFail();
            // finsh card check ====================================================================================

            // check if user in last hour make Reservation ========================================================
            $lastHourReservation = UserAttendance::where('user_id', $request->user()->id)
                ->where('created_at', '>', now()->subHour())
                ->first();

            if ($lastHourReservation) {
                $lastHourReservation->created_at = now();
                $lastHourReservation->save();
                return finalResponse('success', 200, __('errors.valid_parameter'));
            }
            // finsh update user Reservation =======================================================================
            $conflictingReservation = UserAttendance::where('chair_id', $chair->id)
                ->where('table_id', $table->id)
                ->where('restaurant_id',$restaurant->id)
                ->where('created_at', '>', now()->subHour())
                ->first();

            if ($conflictingReservation) {
                return finalResponse('failed', 405, null, null, __('errors.chair_table_already_reserved'));
            }
            $reserve = UserAttendance::create([
                'user_id' => $request->user()->id,
                'chair_id' => $chair->id,
                'table_id' => $table->id,
                'restaurant_id' => $restaurant->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            if ($reserve) {
                return finalResponse('success', 200, __('errors.valid_parameter'));
            }
        } catch (\Exception $e) {
            return finalResponse('failed', 405, null, null, $e->getMessage());
        }
    }

}
