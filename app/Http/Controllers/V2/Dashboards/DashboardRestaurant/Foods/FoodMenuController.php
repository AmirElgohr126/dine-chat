<?php
namespace App\Http\Controllers\V2\Dashboards\DashboardRestaurant\Foods;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Food;

class FoodMenuController extends Controller
{
    public function menu(Request $request)
    {
        try {
            $per_page = $request->per_page ?? 9;
            $user = $request->user('restaurant');
            $food = Food::where('restaurant_id', $user->restaurant_id)
                ->with(['rating' => function ($query) {
                    $query->selectRaw('food_id, AVG(rating) as average_rating, COUNT(id) as rating_count')
                        ->groupBy('food_id');
                }])
                ->with(['images', 'translations' => function ($query) {
                    $query->where('locale', app()->getLocale());
                }])->orderBy('created_at', 'desc')
                ->paginate($per_page);
            $pagnation = pagnationResponse($food);
            return finalResponse('success', 200, $food->items(), $pagnation);
        } catch (Exception $e) {
            return finalResponse('faield', 400, null, null, $e->getMessage());
        }
    }
}
