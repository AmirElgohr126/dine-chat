<?php
namespace App\Http\Controllers\V1\Dashboards\DashboardAdmin\Resturants\Restaurants;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\jsonResponse;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionRestaurant;
use App\Models\SubscriptionNotification;

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
    public function addSubscrptionToRestaurant(Request $request)
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
     * update Subscrption To Restaurant
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function updateSubscrptionToRestaurant(Request $request)
    {
        $validatedData = $request->validate([
            'subscription_id' => 'required|exists:subscription_restaurants,id',
            'restaurant_id' => 'required|exists:subscription_restaurants,id',
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
        $subscription->package;
        $subscription->is_active = $subscription->ends_at->isFuture() ? 1 : 0;
        $subscription->save();
        return finalResponse('success', 200, $subscription);
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
     * add Notification Subscrption To Restaurant
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function addNotificationSubscrptionToRestaurant(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'package_id' => 'required|exists:notification_packages,id',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'required|boolean'
        ]);
        $subscription = SubscriptionNotification::firstOrNew([
            'restaurant_id' => $validatedData['restaurant_id']
        ]);
        $subscription->fill([
            'package_id' => $validatedData['package_id'],
            'starts_at' => Carbon::parse($validatedData['starts_at']),
            'ends_at' => Carbon::parse($validatedData['ends_at']),
            'is_active' => $validatedData['is_active'],
        ])->save();
        $subscription->load('restaurant', 'package');
        return finalResponse('success',200, 'Subscription added successfully.');
    }



    /**
     * delete Notification Subscrption To Restaurant
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function deleteNotificationSubscrptionToRestaurant(Request $request)
    {
        $validatedData = $request->validate([
            'subscription_id' => 'required|exists:subscription_notifications,id',
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);
        $subscription = SubscriptionNotification::where('id', $validatedData['subscription_id'])
            ->where('restaurant_id', $validatedData['restaurant_id'])->first();
        if (!$subscription) {
            return finalResponse('failed',400,'Subscription not found');
        }
        $subscription->delete();
        return finalResponse('success', 400, 'Subscription deleted temporarily');
    }



    /**
     * update Notification Subscrption To Restaurant
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function updateNotificationSubscrptionToRestaurant(Request $request)
    {
        $validatedData = $request->validate([
            'subscription_id' => 'required|exists:subscription_notifications,id',
            'restaurant_id' => 'required|exists:restaurants,id',
            'package_id' => 'required|exists:notification_packages,id',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'required|boolean'
        ]);
        $subscription = SubscriptionNotification::where('id', $validatedData['subscription_id'])
            ->where('restaurant_id', $validatedData['restaurant_id'])
            ->first();
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
     * view Notification Subscrption Of Restaurant
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function viewNotificationSubscrptionOfRestaurant(Request $request)
    {
        $validatedData = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);
        $subscription = SubscriptionNotification::where('restaurant_id', $validatedData['restaurant_id'])->first();
        if (!$subscription) {
            return finalResponse('failed', 404, 'No subscription found for the specified restaurant.');
        }
        $subscription->is_active = $subscription->ends_at->isFuture() ?  1 : 0;
        $subscription->save();
        $subscription->package;
        return finalResponse('success', 200, $subscription);
    }

    // ===========================================================

}

