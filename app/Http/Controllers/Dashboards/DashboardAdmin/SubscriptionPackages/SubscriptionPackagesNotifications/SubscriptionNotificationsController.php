<?php
namespace App\Http\Controllers\Dashboards\DashboardAdmin\SubscriptionPackages\SubscriptionPackagesNotifications;

use Illuminate\Http\Request;
use Illuminate\Http\jsonResponse;
use App\Models\NotificationPackage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\DashboardAdmin\Packages\NotificationPackageResource;

/**
 * Subscription Notifications Controller
 */
class SubscriptionNotificationsController extends Controller
{


    /**
     * list Subscription Package Of Notifications
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function listSubscriptionNotificationsPackage(Request $request)
    {
        $perPage = $request->per_page ?? 10;
        $packages = NotificationPackage::paginate($perPage);

        if ($packages->isEmpty()) {
            return finalResponse('failed', 404, null, null, 'No notification packages found');
        }
        $paginationResponse = pagnationResponse($packages);
        $packagesResource = NotificationPackageResource::collection($packages);

        return finalResponse('success', 200, $packagesResource->items(), $paginationResponse);
    }


    /**
     * add Subscription Package Notifications
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function addSubscriptionNotificationsPackage(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|string|max:255',
            'description' => 'required|string',
            'notification_limit' => 'required|integer|min:1',
            'price_per_month' => 'required|numeric|min:0',
            'price_per_year' => 'required|numeric|min:0',
            'period_finished_deleted_after' => 'required|integer|min:0',
            'period_finished_unit' => 'required|in:hour,day,week,month,year',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $path = storeFile($photo, "packages/notifications", 'public');
            $validatedData['photo'] = $path;
        }
        $notificationPackage = NotificationPackage::create($validatedData);

        return finalResponse('success', 200, 'Notification package added successfully.');
    }


    /**
     * update Subscription Notifications Package
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function updateSubscriptionNotificationsPackage(Request $request)
    {
        $validatedData = $request->validate([
            'package_id' => 'required|exists:notification_packages,id',
            'name' => 'required|string|max:255',
            'photo' => 'nullable|string|max:255',
            'description' => 'required|string',
            'notification_limit' => 'required|integer|min:1',
            'price_per_month' => 'required|numeric|min:0',
            'price_per_year' => 'required|numeric|min:0',
            'period_finished_deleted_after' => 'required|integer|min:0',
            'period_finished_unit' => 'required|in:hour,day,week,month,year',
            'status' => 'required|in:active,inactive',
        ]);

        $notificationPackage = NotificationPackage::findOrFail($validatedData['package_id']);

        if ($request->hasFile('photo')) {
            $newPhoto = $request->file('photo');
            $oldPhoto = $notificationPackage->photo;
            $path = storeFile($newPhoto, "packages/notifications", 'public');
            $notificationPackage->photo = $path;
            if ($oldPhoto) {
                Storage::disk('public')->delete($oldPhoto);
            }
        }

        unset($validatedData['package_id'], $validatedData['photo']);
        $notificationPackage->update($validatedData);

        return finalResponse('success', 200, 'Notification package updated successfully.');
    }


    /**
     * delete Subscription Notifications Package
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function deleteSubscriptionNotificationsPackage(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:notification_packages,id',
        ]);

        $package = NotificationPackage::findOrFail($validated['package_id']);

        if ($package->photo) {
            Storage::disk('public')->delete($package->photo);
        }

        $package->forceDelete();
        return finalResponse('success', 200, 'Notification package deleted successfully.');
    }


    /**
     * archive Subscription Notifications Package
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function archiveSubscriptionNotificationsPackage(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:notification_packages,id',
        ]);

        $package = NotificationPackage::findOrFail($validated['package_id']);
        $package->delete();
        return finalResponse('success', 200, 'Notification package archived successfully.');

    }



    /**
     * list Subscription Notifications Package
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function listarchiveSubscriptionNotificationsPackage(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $archivedPackages = NotificationPackage::onlyTrashed()->paginate($perPage);

        // Assuming NotificationPackageResource exists for formatting the response
        return finalResponse('success', 200, NotificationPackageResource::collection($archivedPackages));

    }



    /**
     * unarchive Subscription Notifications Package
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function unarchiveSubscriptionNotificationsPackage(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:notification_packages,id',
        ]);

        $package = NotificationPackage::onlyTrashed()->where('id', $validated['package_id'])->firstOrFail();
        $package->restore();

        return finalResponse('success', 200, 'Notification package unarchived successfully.');

    }


}

