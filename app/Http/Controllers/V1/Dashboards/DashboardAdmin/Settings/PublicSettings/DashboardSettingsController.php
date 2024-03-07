<?php
namespace App\Http\Controllers\V1\Dashboards\DashboardAdmin\Settings\PublicSettings;

use App\Models\BookingDates;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\ConversationDeleteAfter;

class DashboardSettingsController extends Controller
{
    /**
     * Update the time settings for deleting messages.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateTimeToDeleteMessages(Request $request)
    {
        $request->validate([
            'period_reservation_deleted_after' => 'required|integer',
            'period_reservation_unit' => 'required|string|in:year,month,week,day,hour'
        ]);

        $model = ConversationDeleteAfter::firstOrCreate([]);
        $model->period_reservation_deleted_after = $request->period_reservation_deleted_after;
        $model->period_reservation_unit = $request->period_reservation_unit;
        $model->save();
        return finalResponse('success', 200, 'The time settings for deleting messages has been updated successfully');
    }
    /**
     * Update the time settings for deleting messages.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateTimeToDeleteMessagesForFollowers(Request $request)
    {
        $request->validate([
            'period_reservation_deleted_after_followers' => 'required|integer',
            'period_reservation_unit_followers' => 'required|string|in:year,month,week,day,hour'
        ]);

        $model = ConversationDeleteAfter::firstOrCreate([]);
        $model->period_reservation_deleted_after_followers = $request->period_reservation_deleted_after_followers;
        $model->period_reservation_unit_followers = $request->period_reservation_unit_followers;
        $model->save();
        return finalResponse('success', 200, 'The time settings for deleting messages has been updated successfully');
    }

    /**
     * Update the logout time settings for restaurants
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateLogoutTimes(Request $request)
    {
        $request->validate([
            'period_logout' => 'required|integer',
            'period_logout_unit' => 'required|string|in:year,month,week,day,hour'
        ]);

        $model = BookingDates::firstOrCreate([]);
        $model->period_reservation_deleted_after = $request->period_logout;
        $model->period_reservation_unit = $request->period_logout_unit;
        $model->save();
        return finalResponse('success', 200, 'The logout time settings for restaurants has been updated successfully');
    }
    /**
     * Update the logout time settings for public places.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateLogoutTimesFromPublicPlaces(Request $request)
    {
        $request->validate([
            'period_logout_public_places' => 'required|integer',
            'period_logout_unit_public_places' => 'required|string|in:year,month,week,day,hour'
        ]);

        $model = BookingDates::firstOrCreate([]);
        $model->period_reservation_deleted_after_public_places = $request->period_logout_public_places;
        $model->period_reservation_unit_public_places = $request->period_logout_unit_public_places;
        $model->save();
        return finalResponse('success', 200, 'The logout time settings for public places has been updated successfully');
    }

    /**
     * Enable or disable the application from a specific version.
     *
     * @param Request $request
     * @return void
     */
    public function stopApplicationFromVersion(Request $request)
    {
        // This will involve checking the version provided against the current version and setting a flag
    }

    /**
     * Change the user's password.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changePassword(Request $request)
    {
        // Validate the request to ensure the current password is correct and the new password is valid
        // Then update the user's password
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8'
        ]);

        $user = $request->user('super_admins');
        if (!Hash::check($request->current_password, $user->password)) {
            return finalResponse('error', 400, 'The current password is incorrect');
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        return finalResponse('success', 200, 'Password has been updated successfully');
    }

    /**
     * Change the user's email.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changeEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users'
        ]);

        $user = $request->user('super_admins');
        $user->email = $request->email;
        $user->save();
        return finalResponse('success', 200, 'Email has been updated successfully');
    }

    /**
     * Change the user's name.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changeName(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $user = $request->user('super_admins');
        $user->name = $request->name;
        $user->save();
        return finalResponse('success', 200, 'Name has been updated successfully');
    }

    /**
     * Change the user's profile photo.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image'
        ]);
        // Save the photo and update the user's profile
        $user = $request->user('super_admins');

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $path = storeFile($photo, "users/user{$user->id}/photo", 'public');
            $user->photo = $path;
            $user->save();
        }
        return finalResponse('success', 200, 'Photo has been updated successfully');
    }

}

