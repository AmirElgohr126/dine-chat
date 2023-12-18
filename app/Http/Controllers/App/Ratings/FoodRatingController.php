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
            $foodRatings = FoodRating::where('user_id', $targetUserId)
                ->where('restaurant_id', $restaurantId)
                ->with([
                    'food',  // Load the Food relationship
                    'food.images',  // Load the images relationship for the Food
                    'food.translations' => function ($query) {
                        $query->where('locale', app()->getLocale());
                    },
                ]) // Eager loading
                ->get();
            if (!$foodRatings) {
                return finalResponse('success', 200, 'no ratings');
            }
            return finalResponse('success', 200, $foodRatings);
        } catch (\Exception $e) {
            finalResponse('failed', $e->getCode(), null, null, $e->getMessage());
        }

    }
}
