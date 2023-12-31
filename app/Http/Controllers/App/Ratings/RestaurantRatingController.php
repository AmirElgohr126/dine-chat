<?php
namespace App\Http\Controllers\App\Ratings;

use Exception;
use App\Models\User;
use App\Models\FoodImage;
use App\Models\FoodRating;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Food;

class RestaurantRatingController extends Controller
{
        // public function restaurantsRating(Request $request)
        // {
        //     $request->validate([
        //         'user_id' => ['required','exists:users,id'],
        //         'restaurant_id' => ["required","exists:restaurants,id"]
        //     ]);
        //     try {
        //         $targetUserId = $request->user_id;

        //         $restaurantIds = FoodRating::where('user_id', $targetUserId)->distinct()->pluck('restaurant_id');
        //         $restaurants = [];
        //         foreach ($restaurantIds as $id) {
        //             $restaurant = Restaurant::select('id', 'images')->find($id);
        //             $ratings = FoodRating::where('restaurant_id', $id)->pluck('rating');

        //             // Calculate the ratings
        //             $sumOfRatings = $ratings->sum();
        //             $countOfRatings = $ratings->count();
        //             $averageRating = $countOfRatings > 0 ? $sumOfRatings / $countOfRatings : 0;

        //             // collect images food
        //             $restaurantFoods = Food::where('restaurant_id', $id)->get();
        //             $imageUrls = [];
        //             foreach ($restaurantFoods as $food) {
        //                 if ($food->images) {
        //                     $imageUrls[] = $food->images->image;
        //                 }
        //             }


        //             $restaurants[] = [
        //                 "restaurant" => $restaurant,
        //                 "images" => $imageUrls, // Array of image URLs
        //                 "rating" => $averageRating
        //             ];
        //         }

        //             return finalResponse('success', 200, $restaurants);

        //     } catch (Exception $e) {
        //         return finalResponse('failed', 400, null, null, $e->getMessage());
        //     }
        // }
    public function restaurantsRating(Request $request)
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

                $restaurants[] = [
                    "restaurant" => $restaurant,
                    "images" => $imageUrls,
                    "rating" => round($averageRating, 2)
                ];
            }

            return finalResponse('success', 200, $restaurants);

        } catch (Exception $e) {
            return finalResponse('failed', 400, null, null, $e->getMessage());
        }
    }

}

?>
