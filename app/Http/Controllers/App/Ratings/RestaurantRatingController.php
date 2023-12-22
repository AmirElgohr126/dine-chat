<?php
namespace App\Http\Controllers\App\Ratings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Validation\Rule;
use Exception;
use Illuminate\Support\Facades\DB;

class RestaurantRatingController extends Controller
{
    public function restaurantsRating(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'user_id' => ['required',
                Rule::exists('users', 'id')->where(function ($query) use ($user) {
                    $query->where('id', '!=', $user->id);})
                ],
            'restaurant_id' => ["required","exists:restaurants,id"]
        ]);
        try {
            $targetUserId = $request->user_id;
            $restaurantId = $request->restaurant_id;
            $ratingsWithRestaurants = DB::table('users')
                ->join('restaurant_ratings', 'users.id', '=', 'restaurant_ratings.user_id')
                ->join('restaurants', 'restaurant_ratings.restaurant_id', '=', 'restaurants.id')
                ->join('restaurant_translations', 'restaurants.id', '=', 'restaurant_translations.restaurant_id')
                ->leftJoin('foods', 'restaurants.id', '=', 'foods.restaurant_id')
                ->leftJoin('food_images', 'foods.id', '=', 'food_images.food_id')
                ->select(
                    'users.id as user_id',
                    'restaurant_ratings.id as rating_id',
                    'restaurant_ratings.rating as rating',
                    'restaurants.id as restaurant_id',
                    'restaurants.images as restaurant_images',
                    'restaurants.phone as restaurant_phone',
                    'restaurant_translations.name as restaurant_name',
                    DB::raw('GROUP_CONCAT(food_images.image) as foods_images') // Concatenate food images
                )
                ->where('users.id', $targetUserId)
                ->where('locale', app()->getLocale())
                ->groupBy('restaurants.id', 'users.id', 'restaurant_ratings.id', 'restaurant_translations.name', 'restaurants.images', 'restaurants.phone')
                ->orderByRaw("restaurants.id = ? DESC", [$restaurantId])
                ->get();
            if (isset($ratingsWithRestaurants)) {
                return finalResponse('success', 200, $ratingsWithRestaurants);
            }
            throw new Exception(__('errors.no_review_for_user'), 204);
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), null, null, $e->getMessage());
        }
    }

}

?>
