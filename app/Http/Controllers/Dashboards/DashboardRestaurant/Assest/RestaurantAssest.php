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

            DB::beginTransaction(); // Start transaction

            $restaurant = Restaurant::findOrFail($restaurantId);
            $restaurant->update([
                'hall_width' => $restaurantDimensions['hallWidth'],
                'hall_hight' => $restaurantDimensions['hallHight']
            ]);

            // Process assets if they are present
            if (isset($data['assets'])) {
                $tables = $this->extractAssets($data['assets'], 'Table');
                $chairs = $this->extractAssets($data['assets'], 'Chair');

                $restaurant->tables()->delete();
                $restaurant->chairs()->delete();

                $restaurant->tables()->createMany($tables);
                $restaurant->chairs()->createMany($chairs);
            }else{
                $restaurant->tables()->delete();
                $restaurant->chairs()->delete();
            }
            DB::commit();
            return finalResponse('success', 200, 'success insert hall assets');
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

            $assets = [];

            // Add tables to assets array
            if ($restaurant->tables) {
                foreach ($restaurant->tables as $table) {
                    $assets[] = [
                        'id' => $table->id, // or a suitable identifier
                        'x' => $table->x,
                        'y' => $table->y,
                        'img' => $table->img, // replace with actual image URL attribute
                        'key' => $table->key,
                        'name' => $table->name
                    ];
                }
            }

            if ($restaurant->chairs) {

                foreach ($restaurant->chairs as $chair) {
                    $assets[] = [
                        'id' => $chair->id, // or a suitable identifier
                        'x' => $chair->x,
                        'y' => $chair->y,
                        'img' => $chair->img, // replace with actual image URL attribute
                        'key' => $chair->key,
                        'nfc_number' => $chair->nfc_number,
                        'name' => $chair->name
                    ];
                }
            }

            $response = [
                'assets' => $assets,
                'boardWidth' => $restaurant->hall_width, // or the appropriate attribute
                'boardHeight' => $restaurant->hall_hight, // or the appropriate attribute
            ];

            return finalResponse('success', 200, $response);
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

    private function getAssets($restaurant)
    {
        $assets = [];

        foreach ($restaurant->tables as $table) {
            $assets[] = $this->formatAsset($table, 'Table');
        }

        foreach ($restaurant->chairs as $chair) {
            $assets[] = $this->formatAsset($chair, 'Chair');
        }

        return $assets;
    }
    private function formatAsset($item, $key)
    {
        return [
            'id' => $item->id,
            'x' => $item->x,
            'y' => $item->y,
            'img' => $item->img_url,
            'key' => $key,
        ];
    }
}
