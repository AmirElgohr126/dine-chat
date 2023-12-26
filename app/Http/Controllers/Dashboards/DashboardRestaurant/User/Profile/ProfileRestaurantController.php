<?php
namespace App\Http\Controllers\Dashboards\DashboardRestaurant\User\Profile;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileRestaurantController extends Controller
{
    public function updateProfile(Request $request)
    {
        try {
            $request->validate([
                'phone' => ['required', 'numeric'],
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:6144',
                'name' => ['required', 'regex:/^[a-zA-Z\s]+$/u', 'string', 'max:255'],
            ]);
            $user = $request->user('restaurant');
            if ($request->hasFile('photo')) {
                $photo = $request->photo;
                $path = storeFile($photo, 'restaurant_users', 'public');
                $oldPath = $user->photo;
                if ($oldPath != 'Dafaults/User/user.png') {
                    Storage::disk('public')->delete($oldPath);
                }
                $user->photo = $path;
                $user->save();
            }
            $user->update([
                'phone' => $request->phone,
                'name' => $request->name,
            ]);
                return finalResponse('success', 200,__('errors.update_succeesfully'));

        } catch (Exception $e) {
            return finalResponse('failed', 400, null, null, $e->getMessage());
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'old_password' => 'required',
                'new_password' => 'required|string|min:8|confirmed',
            ]);
            $user = $request->user('restaurant');
            // Check if the old password matches the current password
            if (!Hash::check($request->old_password, $user->password)) {
                throw new Exception(__('errors.current_password_incorrect'));
            }

            // Hash the new password
            $newPasswordHash = Hash::make($request->new_password);

            // Update the user's password
            $user->update([
                'password' => $newPasswordHash,
            ]);

            return finalResponse('success', 200, __('errors.password_changed_success'));
        } catch (Exception $e) {
            return finalResponse('failed', 400, null,null,$e->getMessage());

        }
    }

}

?>
