<?php

namespace App\Http\Controllers\V1\App\Ratings;

use App\Models\Food;
use App\Models\FoodRating;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;


class FoodRatingController extends Controller
{
    /**
     *  Retrieve the ratings of a user for all foods in a restaurant.
     * @param Request $request
     * @return JsonResponse
     */
    public function foodsRating(Request $request) : JsonResponse
    {
        $user = $request->user();
        $request->validate([
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) use ($user) {
                    $query->where('id', '!=', $user->id);
                }),
            ],
            'restaurant_id' => ["required", "exists:restaurants,id"],
        ]);
        try {
            $targetUserId = $request->user_id;
            $restaurantId = $request->restaurant_id;
            $foodRatings = FoodRating::where('user_id', $targetUserId)->where('restaurant_id', $restaurantId)->get();
            $foodData = [];
            foreach ($foodRatings as $rating) {
                $food = $rating->food;
                $imageUrl = $food->images ? $food->images->image : null; // Get the image URL
                $foodData[] = [
                    'id' => $food->id,
                    'restaurant_id' => $food->restaurant_id,
                    'price' => $food->price,
                    'rating' => $rating->rating,
                    'image' => $imageUrl,
                    'name' => $food->name
                ];
            }

            if (empty($foodData)) {
                return finalResponse('success', 200, __('errors.no_ratings'));
            }
            return finalResponse('success', 200, $foodData);
        } catch (\Exception $e) {
            finalResponse('failed', 500, null, null, $e->getMessage());
        }
    }




    /**
     * Retrieve the foods of a restaurant with user ratings.
     * @param Request $request
     * @return JsonResponse
     */
    public function getFoodOfRestaurant(Request $request): JsonResponse
    {
        $restaurantId = $request->restaurant_id;
        $foods = Food::getFoodsWithUserRatings($restaurantId);
        $data = [];

        foreach ($foods as $food) {
            $foodData = [
                'id' => (int) $food->id,
                'name' => (string) $food->name,
                'restaurant_id' => (int) $food->restaurant_id,
                'price' => (float) $food->price,
                'status' => $food->status,
                'image' => $food->images ? (string) $food->images->image : "", // Check if images exist
                'rating' => $food->userRating ? (float) $food->userRating->rating : 0, // Check if userRating exists
            ];
            $data[] = $foodData; // Add each food item to the array
        }

        return finalResponse('success', 200, $data);
    }


    /**
     * Make a rating for food.
     * @param Request $request
     * @return JsonResponse
     */
    public function makeRatingForFood(Request $request): JsonResponse
    {
        $request->validate([
            'food_id' => ['required', 'exists:foods,id'],
            'rating' => ['required', 'digits_between:0,5'],
        ]);
        $userId = $request->user()->id;
        $food = Food::findFoodById($request->food_id, $request->restaurant_id);
        $rate = FoodRating::updateOrCreate(
            [
                'user_id' => $userId,
                'food_id' => $food->id,
            ],
            [
                'rating' => $request->rating,
                'restaurant_id' => $food->restaurant_id,
            ]
        );
        return finalResponse('success', 200, $rate);
    }
}



