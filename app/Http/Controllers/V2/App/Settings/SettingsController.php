<?php

namespace App\Http\Controllers\V2\App\Settings;

use App\Models\UserGhost;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function changeEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        try {
            $user = $request->user();
            $newEmail = User::where('email', $request->email)->first();
            if (isset($newEmail)) {
                throw new Exception(__('errors.email_already_exists'), 405);
            }
            $user->update([
                'email' => $request->email,
            ]);
            $user->email_verified_at = null;
            $user->save();
            $user->sendEmailVerificationNotification();
            return finalResponse('success', 200, 'success change email');
        } catch (Exception $e) {
            return finalResponse('faild', 500, null, null, $e->getMessage());
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => [
                'required',
                'string',
                'min:8',                    // Minimum length
                'regex:/^(?=.*[A-Z])/',     // At least one uppercase letter
                'regex:/^(?=.*[a-z])/',     // At least one lowercase letter
                'regex:/^(?=.*[0-9])/',     // At least one digit
                'regex:/^(?=.*[@$!%*?&])/'], // At least one special character
            'confirm_password' => 'required|same:new_password',
        ]);

        $user = $request->user();

        // Check if the current password matches the user's actual password
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return finalResponse('failed', 401, null, null, __('errors.current_password_incorrect'));
        }

        // Update the user's password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return finalResponse('success', 200, __('errors.password_changed_success'));
    }


    public function changeToGhost(Request $request)
    {
        try {
            $user = $request->user();
            $mood = $user->ghost_mood;
            if ($mood == 0) {
                // convert to ghost
                $ghost = UserGhost::where('user_id', $user->id)->first();
                if ($ghost) {
                    $ghost->delete();
                }
                $ghost = UserGhost::create([
                    'user_id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'phone' => $user->phone,
                    'photo' => $user->photo,
                ]);
                $user->update([
                    'ghost_mood' => 1,
                    'photo' => "Dafaults/Ghost/ghost.png",
                    'first_name' => 'Spectra',
                    'last_name' => 'Shadowvale',
                    'phone' => '00000000000',
                ]);
            } else {
                // convert to user
                $ghost = UserGhost::where('user_id', $user->id)->first();
                $user->update([
                    'ghost_mood' => 0,
                    'photo' => $ghost->photo,
                    'first_name' => $ghost->first_name,
                    'last_name' => $ghost->last_name,
                    'phone' => $ghost->phone,
                ]);
                $ghost->delete();
            }
            return finalResponse('success', 200, __('errors.ghost_mode_updated_success'));
        } catch (Exception $e) {
            return finalResponse('failed', 500, null, null, 'internal server error' . $e->getMessage());
        }
    }

    public function muteNotifications(Request $request)
    {
        $user = $request->user();
        switch ($user->notification_status) {
            case 0:
                $user->notification_status = 1;
                break;
            case 1:
                $user->notification_status = 0;
                break;
        }
        $user->save();
        return finalResponse('success',200,$user->notification_status);
    }
}

