<?php

namespace App\Http\Controllers\App\Ratings;

use App\Models\Food;
use App\Models\FoodRating;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

use function PHPUnit\Framework\isEmpty;
use App\Http\Resources\Food\FoodResource;

class FoodRatingController extends Controller
{
    public function foodsRating(Request $request)
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
            $data = [];
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

            if (isEmpty($foodData)) {
                return finalResponse('success', 200, __('errors.no_ratings'));
            }
            return finalResponse('success', 200, $foodData);
        } catch (\Exception $e) {
            finalResponse('failed', 500, null, null, $e->getMessage());
        }

    }


    /**
     * return all restaurant food that user make rating to it
     *
     * @param Request $request
     */
    // public function getFoodOfrestaurant(Request $request)
    // {
    //     $user = $request->user();
    //     $restaurantId = $request->restaurant_id;

    //     // Retrieve all foods in the restaurant
    //     $foods = Food::where('restaurant_id', $restaurantId)->with('images')->get();

    //     // Retrieve all food ratings in the restaurant made by the user
    //     $foodRatings = FoodRating::where('restaurant_id', $restaurantId)
    //         ->where('user_id', $user->id)
    //         ->get()
    //         ->keyBy('food_id'); // Keying by food_id for easier lookup

    //     // Prepare the response data
    //     $responseData = [];
    //     foreach ($foods as $food) {
    //         // Check if the user has rated this food
    //         $userRating = isset($foodRatings[$food->id]) ? $foodRatings[$food->id]->rating : '';

    //         // Get the first image URL or null if no images
    //         $imageURL = $food->images ? $food->images->image : '';

    //         // Build the food item for the response
    //         $responseData[] = [
    //             'id' => $food->id,
    //             'restaurant_id' => $food->restaurant_id,
    //             'price' => $food->price,
    //             'created_at' => $food->created_at,
    //             'updated_at' => $food->updated_at,
    //             'details' => $food->details ?? '',
    //             'status' => $food->status,
    //             'user_rating' => $userRating,
    //             'name' => $food->name,
    //             'images' => $imageURL,
    //         ];
    //     }

    //     // Return the final response
    //     return finalResponse('success', 200, $responseData);
    // }
    public function getFoodOfrestaurant(Request $request)
    {
        $user = $request->user();
        $restaurantId = $request->restaurant_id;
        $foods = Food::getFoodsWithUserRatings($restaurantId);

        return FoodResource::collection($foods);
    }


    public function makeRatingForFood(Request $request)
    {
        $request->validate([
            'food_id' => ['required','exists:foods,id'],
            'rating' => ['required','digits_between:0,5'],
        ]);
        $userId = $request->user()->id;
        $food = Food::findFoodById($request->food_id,$request->restaurant_id);
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
        return finalResponse('success',200, $rate);
    }
}


