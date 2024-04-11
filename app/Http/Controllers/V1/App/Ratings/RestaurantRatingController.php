<?php
namespace App\Http\Controllers\V1\App\Ratings;

use Exception;
use App\Models\FoodRating;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Food;

class RestaurantRatingController extends Controller
{
    /**
     * Retrieve the ratings of a user for all restaurants.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function restaurantsRating(Request $request) : JsonResponse
    {

        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'restaurant_id' => ['required', 'exists:restaurants,id']
        ]);

        try {
            $targetUserId = $request->user_id;
            $restaurantIds = FoodRating::where('user_id', $targetUserId)->distinct()->pluck('restaurant_id');
            $restaurants = [];
            foreach ($restaurantIds as $id) {
                $restaurant = Restaurant::select('id', 'images')->find($id);
                // Calculate the average rating
                $ratings = FoodRating::where('restaurant_id', $id)->pluck('rating');
                $averageRating = $ratings->isNotEmpty() ? $ratings->avg() : 0;

                $restaurantFoods = Food::where('restaurant_id', $id)->with('images')->get();
                $imageUrls = $restaurantFoods->flatMap(function ($food) {
                    return $food->images ? [$food->images->image] : [];
                });
                $restaurant->rating = round($averageRating, 2);
                $restaurant->imagesFood = $imageUrls;
                $restaurants[] = $restaurant;
            }
            return finalResponse('success', 200, $restaurants);

        } catch (Exception $e) {
            return finalResponse('failed', 400, null, null, $e->getMessage());
        }
    }

}
