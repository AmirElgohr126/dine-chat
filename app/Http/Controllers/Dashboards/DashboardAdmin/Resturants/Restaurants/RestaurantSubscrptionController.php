<?php
namespace App\Http\Controllers\Dashboards\DashboardAdmin\Resturants\Restaurants;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\jsonResponse;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionRestaurant;

/**
 * Restaurant Subscrption Controller
 */
class RestaurantSubscrptionController extends Controller
{

    /**
     * add Subscrption To Restaurant
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function addSubscriptionToRestaurant(Request $request)
    {
        $validatedData = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'package_id' => 'required|exists:restaurant_packages,id',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'required|boolean',
        ]);

        $subscription = SubscriptionRestaurant::firstOrNew([
            'restaurant_id' => $validatedData['restaurant_id']
        ]);

        $subscription->fill([
            'package_id' => $validatedData['package_id'],
            'starts_at' => Carbon::parse($validatedData['starts_at']),
            'ends_at' => Carbon::parse($validatedData['ends_at']),
            'is_active' => $validatedData['is_active'],
        ])->save();

        return finalResponse('success', 200, 'successfully Subscription!');
    }


    /**
     * delete Subscrption To Restaurant
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function deleteSubscrptionToRestaurant(Request $request)
    {
        $validatedData = $request->validate([
            'subscription_id' => 'required|exists:subscription_restaurants,id',
            'restaurant_id' => 'required|exists:subscription_restaurants,id',
        ]);

        $subscription = SubscriptionRestaurant::where('id', $validatedData['subscription_id'])
            ->where('restaurant_id', $validatedData['restaurant_id'])->first();
        $subscription->delete();

        return finalResponse('success', 200, 'Subscription deleted successfully!');
    }


    /**
     * update Subscrption To Restaurant
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function updateSubscrptionToRestaurant(Request $request)
    {

        $validatedData = $request->validate([
            'subscription_id' => 'required|exists:subscription_restaurants,id',
            'package_id' => 'required|exists:restaurant_packages,id',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'required|boolean',
        ]);
        $subscription = SubscriptionRestaurant::where('id', $validatedData['subscription_id'])->where('restaurant_id', $validatedData['restaurant_id'])->first();
        if (!$subscription) {
            return finalResponse('error', 404, 'Subscription not found!');
        }
        unset($validatedData['subscription_id']);
        $updateStatus = $subscription->update($validatedData);

        if ($updateStatus) {
            return finalResponse('success', 200, 'Subscription updated successfully!');
        } else {
            return finalResponse('error', 500, 'Failed to update the subscription.');
        }
    }


    /**
     * view Subscrption Of Restaurant
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function viewSubscrptionOfRestaurant(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|integer|exists:restaurants,id',
        ]);

        $subscription = SubscriptionRestaurant::where('restaurant_id', $validated['restaurant_id'])->first();

        if (!$subscription) {
            return finalResponse('failed',400,'Subscription not found for the specified restaurant.');
        }
        return finalResponse('success', 200, 'Subscription retrieved successfully.');

    }


    /**
     * add Notification Subscrption To Restaurant
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function addNotificationSubscrptionToRestaurant(Request $request)
    {
        return finalResponse('success', 200, 'successfully Subscription!');

    }
    // ===========================================================


    /**
     * delete Notification Subscrption To Restaurant
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function deleteNotificationSubscrptionToRestaurant(Request $request)
    {
        return finalResponse('success', 200, 'successfully Subscription!');

    }


    /**
     * update Notification Subscrption To Restaurant
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function updateNotificationSubscrptionToRestaurant(Request $request)
    {
        return finalResponse('success', 200, 'successfully Subscription!');

    }


    /**
     * view Notification Subscrption Of Restaurant
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function viewNotificationSubscrptionOfRestaurant(Request $request)
    {
        return finalResponse('success', 200, 'successfully Subscription!');

    }


}

