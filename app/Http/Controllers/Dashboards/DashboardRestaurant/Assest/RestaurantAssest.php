<?php

namespace App\Http\Controllers\Dashboards\DashboardRestaurant\Assest;

use Exception;
use App\Models\Table;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Tables\TablesDashboardRequest;

class RestaurantAssest extends Controller
{
    public function createAssest(TablesDashboardRequest $request)
    {
        try {
            $user = $request->user('restaurant');
            $restaurantId = $user->restaurant_id;
            $data = $request->validated();
            $restaurantDimensions = [
                'hallWidth' => $data['boardWidth'],
                'hallHight' => $data['boardHeight'],
            ];
            $tables = $this->extractAssets($data['assets'], 'Table');
            $chairs = $this->extractAssets($data['assets'], 'Chair');
            DB::beginTransaction(); // Start transaction
            $restaurant = Restaurant::findOrFail($restaurantId);
            $restaurant->update([
                'hall_width' => $restaurantDimensions['hallWidth'],
                'hall_hight' => $restaurantDimensions['hallHight']
            ]);
            $restaurant->tables()->delete();
            $restaurant->chairs()->delete();

            $restaurant->tables()->createMany($tables);
            $restaurant->chairs()->createMany($chairs);
            DB::commit();
            return finalResponse('success',200,'success insert hall assets');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating hall assets: ' . $e->getMessage()); // Log the error for debugging
            return finalResponse('failed', 400, null, null, 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function listAssets(Request $request)
    {
        try {
            $user = $request->user('restaurant');
            $restaurantId = $user->restaurant_id;

            // Fetch the restaurant with its related tables and chairs
            $restaurant = Restaurant::with(['tables', 'chairs'])->findOrFail($restaurantId);

            // Format the response data
            $assets = [
                'restaurantDimensions' => [
                    'hallWidth' => $restaurant->hall_width,
                    'hallHeight' => $restaurant->hall_height,
                ],
                'tables' => $restaurant->tables,
                'chairs' => $restaurant->chairs,
            ];

            return finalResponse('success', 200,$assets);
        } catch (Exception $e) {
            Log::error('Error listing assets: ' . $e->getMessage());
            return finalResponse('failed', 400, null, null, 'Failed to retrieve assets: ' . $e->getMessage());
        }
    }


    private function extractAssets($assets, $type)
    {
        return collect($assets)->filter(function ($asset) use ($type) {
            return str_contains($asset['key'], $type);
        })->all();
    }
}
