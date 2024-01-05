<?php
namespace App\Http\Controllers\Dashboards\DashboardRestaurant\Statistics;

use DateTime;
use App\Models\Food;
use App\Models\FoodRating;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StatisticsController extends Controller
{

    public function restaurantReviewFromFive(Request $request)
    {
        $user = $request->user('restaurant');
        $ratings = FoodRating::where('restaurant_id', $user->restaurant_id)->pluck('rating');
        $averageRating = $ratings->isNotEmpty() ? round($ratings->avg(),2) : 0;
        return finalResponse('success',200,$averageRating);
    }
    public function getRestaurantClients(Request $request)
    {
        $user = $request->user('restaurant');
        $restaurant = Restaurant::find($user->restaurant_id);
        $userAttendanceCount = $restaurant->userAttendance()->distinct('user_id')->count();
        return finalResponse('success',200,$userAttendanceCount);
    }
    public function getRestaurantReveiwCount(Request $request)
    {
        $user = $request->user('restaurant');
        $numberOfRatings = FoodRating::where('restaurant_id', $user->restaurant_id)->count();
        return finalResponse('success', 200, $numberOfRatings);
    }
    public function subscription_statistics(Request $request)
    {
        $user = $request->user('restaurant');

        // Initialize the note variable
        $note = null;

        // Assuming that 'start_subscription' and 'expire_subscription' valid
        $startDate = new DateTime($user->start_subscription);
        $expireDate = new DateTime($user->expire_subscription);
        $currentDate = new DateTime();

        $remainingTime = $expireDate > $currentDate ? $expireDate->diff($currentDate) : null;
        $timeSpent = $currentDate > $startDate ? $currentDate->diff($startDate) : null;

        // Define a threshold for the subscription to be considered as expiring soon
        $expireSoonThreshold = 10; // days

        // Check if the subscription is expiring soon
        if ($remainingTime && $remainingTime->days <= $expireSoonThreshold) {
            $note = 'Your subscription will finish soon.';
        }

        // Format remaining time and time spent for the response
        $RemainingTimeToFinishSubscription = $remainingTime ? $remainingTime->format('%a') : 0;
        $TimeSpentFromSubscription = $timeSpent ? $timeSpent->format('%a') : 0;

        // Prepare the response data
        $subscriptionData = [
            'start_subscription'=> $startDate,
            'end_subscription'=> $expireDate,
            'remaining_time' => $RemainingTimeToFinishSubscription,
            'time_spent' => $TimeSpentFromSubscription,
            'note' => $note
        ];

        return finalResponse('success', 200, $subscriptionData);
    }

    public function recentTransitions(Request $request)
    {
        $per_page = $request->per_page ?? 6;
        $user = $request->user('restaurant');
        $restaurant_id = $user->restaurant_id;
        $foods = Food::where('restaurant_id', $restaurant_id)->paginate($per_page);
        $pagnation = pagnationResponse($foods);
        $foodRatingsDetails = [];

        foreach ($foods as $food) {
            $ratings = FoodRating::where('restaurant_id', $restaurant_id)->where('food_id', $food->id)->pluck('rating');
            $averageRating = $ratings->isNotEmpty() ? $ratings->avg() : 0;
            // Check if the images relation/object exists
            $food->image = $food->images ? $food->images->image : null;
            $food->rate = $averageRating;

            $foodRatingsDetails[] = $food;
        }

        return finalResponse('success',200,$foodRatingsDetails, $pagnation);
    }
    // public function recentTransitions(Request $request)
    // {
    //     $per_page = $request->per_page ?? 6;
    //     $restaurant_id = $request->user('restaurant')->restaurant_id;

    //     // Eager load relationships and calculate average rating using database query
    //     $foods = Food::with('images')
    //         ->where('restaurant_id', $restaurant_id)
    //         ->withCount([
    //             'rating as average_rating' => function ($query) {
    //                 $query->select(DB::raw('coalesce(avg(rating),0)'));
    //             }
    //         ])
    //         ->paginate($per_page);

    //     // Prepare the response data
    //     $foodRatingsDetails = $foods->map(function ($food) {
    //         return [
    //             'food' => $food,
    //             'rating' => $food->average_rating,
    //             'image' => $food->images ? $food->images->image : null
    //         ];
    //     });

    //     return finalResponse('success', 200, $foodRatingsDetails);
    // }
}

?>
