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
            'user_id' => ['required','exists:users,id'],
            'restaurant_id' => ["required","exists:restaurants,id"]
        ]);
        try {
            $targetUserId = $request->user_id;
            $restaurantId = $request->restaurant_id;
            // claculate the avarage rating by equation                          sum of values on   / total number of ratings on this restaurant
            $rating = '';
            if ($rating) {
                return finalResponse('success', 200, $rating);
            }
            throw new Exception(__('errors.no_review_for_user'), 204);
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), null, null, $e->getMessage());
        }
    }

}

?>
