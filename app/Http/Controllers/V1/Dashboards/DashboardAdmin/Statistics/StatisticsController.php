<?php
namespace App\Http\Controllers\V1\Dashboards\DashboardAdmin\Statistics;

use App\Models\Restaurant;
use App\Http\Controllers\Controller;
use App\Models\ApplcationForRestaurant;
use App\Models\HistoryAttendances;
use App\Models\SubscriptionRestaurant;
use App\Models\Ticket;

class StatisticsController extends Controller
{
    /**
     * Get the count of restaurants that have finished their subscription.
     */
    public function getCountRestaurantThatSubscriptionExpired()
    {
        $expiredSubscriptions = SubscriptionRestaurant::where('ends_at', '<', now())->count();
        return finalResponse('success',200,$expiredSubscriptions);
    }

    /**
     * Get the count of unanswered technical support messages that are not waiting.
     */
    public function getCountUnansweredSupportMessages()
    {
        $tickits = Ticket::where('status', 'no_reply')->count();
        return finalResponse('success',200,$tickits);
    }

    /**
     * Get the total count of technical support messages.
     */
    public function getCountTotalSupportMessages()
    {
        $totalTickits = Ticket::count();
        return finalResponse('success',200,$totalTickits);
    }


    /**
     * Get the number of restaurants that are waiting (for approval/activation).
     */
    public function getCountWaitingRestaurants()
    {
        $waitingRestaurants = ApplcationForRestaurant::where('status','pending')->count();
        return finalResponse('success',200,$waitingRestaurants);
    }

    /**
     * Get the total number of restaurants.
     */
    public function getCountofAllRestaurants()
    {
        $totalRestaurants = Restaurant::count();
        return finalResponse('success',200,$totalRestaurants);
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
        $newCustomers = HistoryAttendances::where('created_at', '>=', now()->subWeek())->count();
        return finalResponse('success',200,$newCustomers);
    }

    /**
     * Get the total number of customers.
     */
    public function getTotalCustomers()
    {
        $totalCustomers = HistoryAttendances::count();
        return finalResponse('success',200,$totalCustomers);
    }

    // public function getRatedPlaces()
    // {

    // }
}



