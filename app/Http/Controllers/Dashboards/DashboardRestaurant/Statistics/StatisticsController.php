<?php
namespace App\Http\Controllers\Dashboards\DashboardRestaurant\statistics;

use DateTime;
use App\Models\FoodRating;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatisticsController extends Controller
{

    public function restaurantReviewFromFive(Request $request)
    {
        $user = $request->user('restaurant');
        $ratings = FoodRating::where('restaurant_id', $user->restaurant_id)->pluck('rating');
        $averageRating = $ratings->isNotEmpty() ? $ratings->avg() : 0;
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
        $RemainingTimeToFinishSubscription = $remainingTime ? $remainingTime->format('%a days') : 'Expired';
        $TimeSpentFromSubscription = $timeSpent ? $timeSpent->format('%a days') : 'Not started';

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

    }
}

?>
