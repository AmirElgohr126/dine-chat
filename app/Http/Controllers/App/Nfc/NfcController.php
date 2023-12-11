<?php

namespace App\Http\Controllers\App\Nfc;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NfcController extends Controller
{
    public function VaildateNfcParameter(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'restaurant_id' => 'required|exists:restaurants,id',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'table_number' => 'required|exists:tables,table_number',
        'chair_number' => 'required|exists:chairs,chair_number',
    ]);

    if ($validator->fails()) {
        return finalResponse('failed', 405, null, null, $validator->errors()->first());
    }

    try {
        // Use findOrFail to throw an exception if the restaurant is not found
        $restaurant = Restaurant::findOrFail($request->restaurant_id);

        // Check if either latitude or longitude is incorrect
        if (!($request->latitude == $restaurant->latitude && $request->longitude == $restaurant->longitude)) {
            throw new \Exception(__('errors.invalid_parameter'));
        }

        // Use findOrFail to throw an exception if the table is not found
        $table = $restaurant->tables()->where('table_number', $request->table_number)->firstOrFail();

        // Use findOrFail to throw an exception if the chair is not found
        $chair = $table->chairs()->where('chair_number', $request->chair_number)->firstOrFail();

        return finalResponse('success', 200, __('errors.valid_parameter'));

    } catch (\Exception $e) {
        return finalResponse('failed', 405, null, null, $e->getMessage());
    }

    }
}
