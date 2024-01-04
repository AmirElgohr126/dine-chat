<?php

namespace App\Http\Controllers\App\Ratings;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\FoodRating;

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

            if (!$foodData) {
                return finalResponse('success', 200, __('errors.no_ratings  '));
            }
            return finalResponse('success', 200, $foodData);
        } catch (\Exception $e) {
            finalResponse('failed', $e->getCode(), null, null, $e->getMessage());
        }

    }
}
