<?php
namespace App\Http\Controllers\V2\Dashboards\DashboardAdmin\SubscriptionPackages\SubscriptionPackagesRestaurants;

use Illuminate\Http\Request;
use App\Models\RestaurantPackage;
use Illuminate\Http\jsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\V2\DashboardAdmin\Packages\RestaurantPackageResource;

/**
 * Handles operations related to Package restaurant subscriptions.
 */
class SubscriptionPackageRestaurantsController extends Controller
{


    /**
     * List all subscribed Package restaurants.
     *
     * @param \Illuminate\Http\Request $request The request object containing any filters or pagination.
     * @return jsonResponse
     */
    public function listSubscriptionPackageRestaurant(Request $request)
    {
        $per_page = $request->per_page ?? 10;

        $packages = RestaurantPackage::paginate($per_page);
        if (!$packages) {
            return finalResponse('failed', 400, null, null, 'no packages found');
        }
        $pagnationResponse = pagnationResponse($packages);
        $packages = RestaurantPackageResource::collection($packages);
        return finalResponse('success', 200, $packages->items() , $pagnationResponse);
    }


    /**
     * Add a new subscription for a Package restaurant.
     *
     * @param \Illuminate\Http\Request $request The request object containing subscription details.
     * @return jsonResponse
     */
    public function addSubscriptionPackageRestaurant(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => ['nullable','image','mimes:jpeg,png,jpg,gif,svg','max:6144'],
            'description' => 'required|string',
            'price_per_month' => 'required|numeric|min:0',
            'price_per_year' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'period_finished_after' => 'required|date',
            'features.*' => 'required|string',
            'limitations.*' => 'required|string',
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $path = storeFile($photo, "packages/restaurants", 'public');
            $validatedData['photo'] = $path;
        }
        $restaurantPackage = RestaurantPackage::create($validatedData);

        return finalResponse('success',200);
    }


    /**
     * Update an existing subscription for a Package restaurant.
     *
     * @param \Illuminate\Http\Request $request The request object containing updated subscription details.
     * @return jsonResponse
     */
    public function updateSubscriptionPackageRestaurant(Request $request)
    {
        $validatedData = $request->validate([
            'package_id' => 'required|exists:restaurant_packages,id',
            'name' => 'required|string|max:255',
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:6144'],
            'description' => 'required|string',
            'price_per_month' => 'required|numeric|min:0',
            'price_per_year' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'period_finished_deleted_after' => 'required|integer|min:0',
            'period_finished_unit' => 'required|in:hour,day,week,month,year',
            'features.*' => 'required|string',
            'limitations.*' => 'required|string',
        ]);
        $restaurantPackage = RestaurantPackage::findOrFail($validatedData['package_id']);

        if ($request->hasFile('photo')) {
            $new = $request->file('photo');
            $old = $restaurantPackage->photo;
            $path = storeFile($new, "packages/restaurants", 'public');
            $restaurantPackage->photo = $path;
            $restaurantPackage->save();
            if ($old) {
                Storage::disk('public')->delete($old);
            }
        }

        unset($validatedData['package_id']);
        unset($validatedData['photo']);

        $restaurantPackage->update($validatedData);
        return finalResponse('success', 200, 'Package updated successfully.');
    }


    /**
     * Delete a Package restaurant's subscription.
     *
     * @param \Illuminate\Http\Request $request The request object containing the ID of the subscription to delete.
     * @return jsonResponse
     */
    public function deleteSubscriptionPackageRestaurant(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:restaurant_packages,id',
        ]);

        $package = RestaurantPackage::findOrFail($validated['package_id']);

        if ($package->photo) {
            Storage::disk('public')->delete($package->photo);
        }

        $package->forceDelete();
        return finalResponse('success', 200,'Package deleted successfully.');
    }


    /**
     * archive a Package restaurant's subscription.
     *
     * @param \Illuminate\Http\Request $request The request object containing the ID of the subscription to delete.
     * @return jsonResponse
     */
    public function archiveSubscriptionPackageRestaurant(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|integer',
        ]);

        $package = RestaurantPackage::findOrFail($validated['package_id']);

        $package->delete();

        return finalResponse('success', 200, 'Package archived successfully.');
    }


    /**
     * list archived a Package restaurant's subscription.
     *
     * @param \Illuminate\Http\Request $request The request object containing the ID of the subscription to delete.
     * @return jsonResponse
     */
    public function listarchiveSubscriptionPackageRestaurant(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Default to 10 items per page if not specified
        $archivedPackages = RestaurantPackage::onlyTrashed()->orderByDesc('created_at')
            ->paginate($perPage);

        // Assuming you have a RestaurantPackageResource or similar to format the response
        return finalResponse('success', 200, RestaurantPackageResource::collection($archivedPackages));
    }


    /**
     * unarchive a Package restaurant's subscription.
     *
     * @param \Illuminate\Http\Request $request The request object containing the ID of the subscription to delete.
     * @return jsonResponse
     */
    public function unarchiveSubscriptionPackageRestaurant(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|integer',
        ]);

        $package = RestaurantPackage::onlyTrashed()->where('id', $validated['package_id'])->first();

        if (!$package)
        {
            return finalResponse('success',200,'Package not found or not archived.');
        }

        $package->restore();

        // Return a success response
        return finalResponse('success', 200, 'Package unarchived successfully.');
    }
}

