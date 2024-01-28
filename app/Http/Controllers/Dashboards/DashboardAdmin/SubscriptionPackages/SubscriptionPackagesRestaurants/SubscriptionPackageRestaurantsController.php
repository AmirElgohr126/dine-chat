<?php
namespace App\Http\Controllers\Dashboards\DashboardAdmin\SubscriptionPackages\SubscriptionPackagesRestaurants;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Handles operations related to Package restaurant subscriptions.
 */
class SubscriptionPackageRestaurantsController extends Controller
{
    /**
     * List all subscribed Package restaurants.
     *
     * @param \Illuminate\Http\Request $request The request object containing any filters or pagination.
     * @return void
     */
    public function listSubscriptionPackageRestaurant(Request $request)
    {
        // Your code to fetch and return the list of subscribed Packagerestaurants...
    }


    /**
     * Add a new subscription for a Package restaurant.
     *
     * @param \Illuminate\Http\Request $request The request object containing subscription details.
     * @return void
     */
    public function addSubscriptionPackageRestaurant(Request $request)
    {
        // Your code to add a new subscription...
    }



    /**
     * Update an existing subscription for a Package restaurant.
     *
     * @param \Illuminate\Http\Request $request The request object containing updated subscription details.
     * @return void
     */
    public function updateSubscriptionPackageRestaurant(Request $request)
    {
        // Your code to update the subscription...
    }


    /**
     * Delete a Package restaurant's subscription.
     *
     * @param \Illuminate\Http\Request $request The request object containing the ID of the subscription to delete.
     * @return void
     */
    public function deleteSubscriptionPackageRestaurant(Request $request)
    {
        // Your code to delete the subscription...
    }
}

