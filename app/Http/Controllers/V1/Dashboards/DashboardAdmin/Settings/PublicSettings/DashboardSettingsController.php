<?php
namespace App\Http\Controllers\V1\Dashboards\DashboardAdmin\Settings\PublicSettings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardSettingsController extends Controller
{
    /**
     * Update the time settings for deleting messages.
     *
     * @param Request $request
     * @return void
     */
    public function updateTimeToDeleteMessages(Request $request)
    {
        // You will need to validate the request and save the settings
        // Example: $user->settings()->update(['message_delete_time' => $request->input('time')]);
        // Return a void indicating success or failure
    }
    /**
     * Update the time settings for deleting messages.
     *
     * @param Request $request
     * @return void
     */
    public function updateTimeToDeleteMessagesForFollowers(Request $request)
    {
        // You will need to validate the request and save the settings
        // Example: $user->settings()->update(['message_delete_time' => $request->input('time')]);
        // Return a void indicating success or failure
    }

    /**
     * Update the logout time settings for restaurants
     *
     * @param Request $request
     * @return void
     */
    public function updateLogoutTimes(Request $request)
    {
        // Similar to the message deletion, validate and save the logout times
    }
    /**
     * Update the logout time settings for public places.
     *
     * @param Request $request
     * @return void
     */
    public function updateLogoutTimesFromPublicPlaces(Request $request)
    {
        // Similar to the message deletion, validate and save the logout times
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
     * @return void
     */
    public function changePassword(Request $request)
    {
        // Validate the request to ensure the current password is correct and the new password is valid
        // Then update the user's password
    }

    /**
     * Change the user's email.
     *
     * @param Request $request
     * @return void
     */
    public function changeEmail(Request $request)
    {
        // Validate and update the user's email
    }

    /**
     * Change the user's name.
     *
     * @param Request $request
     * @return void
     */
    public function changeName(Request $request)
    {
        // Validate and update the user's name
    }

    /**
     * Change the user's profile photo.
     *
     * @param Request $request
     * @return void
     */
    public function changePhoto(Request $request)
    {
        // Validate the uploaded photo and update the user's profile photo
    }

}

