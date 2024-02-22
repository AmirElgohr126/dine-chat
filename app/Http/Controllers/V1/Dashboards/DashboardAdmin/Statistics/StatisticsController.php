<?php
namespace App\Http\Controllers\V1\Dashboards\DashboardAdmin\Statistics;

use App\Http\Controllers\Controller;

class StatisticsController extends Controller
{
    /**
     * Get the count of restaurants that have finished their subscription.
     */
    public function getCountRestaurantThatSubscriptionExpired()
    {
        // $expiredSubscriptions = Restaurant::where('subscription_end', '<', now())->count();
        // return response()->json(['expired_subscriptions' => $expiredSubscriptions]);
    }

    /**
     * Get the count of unanswered technical support messages that are not waiting.
     */
    public function getCountUnansweredSupportMessages()
    {
        // $unansweredMessages = SupportMessage::where('answered', false)->where('status', '!=', 'waiting')->count();
        // return response()->json(['unanswered_messages' => $unansweredMessages]);
    }

    /**
     * Get the total count of technical support messages.
     */
    public function getCountTotalSupportMessages()
    {
        // $totalMessages = SupportMessage::count();
        // return response()->json(['total_messages' => $totalMessages]);
    }


    /**
     * Get the number of restaurants that are waiting (for approval/activation).
     */
    public function getCountWaitingRestaurants()
    {
        // $waitingRestaurants = Restaurant::where('status', 'waiting')->count();
        // return response()->json(['waiting_restaurants' => $waitingRestaurants]);
    }

    /**
     * Get the total number of restaurants.
     */
    public function getCountofAllRestaurants()
    {
        // $totalRestaurants = Restaurant::count();
        // return response()->json(['total_restaurants' => $totalRestaurants]);
    }


    /**
     * Get statistics for entries into public places.
     */
    public function getPublicPlacesEntries()
    {
        // This will depend greatly on how you track entries in your system.
    }


    /**
     * Get statistics for entry processes for restaurants.
     * get hights entries count and ratings
     */
    public function getRestaurantEntries()
    {
        // Example:
        // $ratedPlaces = Place::select('name', 'rating')
        //                      ->groupBy('rating')
        //                      ->orderBy('rating', 'desc')
        //                      ->get()
        //                      ->map(function ($place) {
        //                         return [
        //
        // This will also depend on the specifics of how entries are logged in your system.
    }


    /**
     * Get the count of new customers from the last week.
     */
    public function getCountNewCustomersLastWeek()
    {
        // $newCustomers = Customer::where('created_at', '>=', now()->subWeek())->count();
        // return response()->json(['new_customers_last_week' => $newCustomers]);
    }

    /**
     * Get the total number of customers.
     */
    public function getTotalCustomers()
    {
        // $totalCustomers = Customer::count();
        // return response()->json(['total_customers' => $totalCustomers]);
    }

    // public function getRatedPlaces()
    // {

    // }
}



