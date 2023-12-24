<?php
namespace App\Http\Controllers\Dashboards\DashboardRestaurant\User\Profile;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProfileRestaurantController extends Controller
{
    public function updateProfile(Request $request)
    {
        try {
            $request->validate([
                'phone' => ['required', 'numeric'],
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:6144',
                'name' => ['required', 'string', 'max:255'],
            ]);
            $user = $request->user('restaurant');
            $path = $user->photo;
            if ($request->hasFile('photo')) {
                $photo = $request->photo;
                $path = storeFile($photo, 'restaurant_users', 'public');
                $oldPath = $user->photo;
                if ($oldPath != 'Dafaults/User/user.png') {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            dd($path);
            $updated = $user->update([
                'phone' => $request->phone,
                'photo' => $path,
                'name' => $request->name,
            ]);

            if ($updated) {
                return finalResponse('success', 200);
            } else {
                return finalResponse('failed', 400, null, null, 'Update failed');
            }
        } catch (Exception $e) {
            return finalResponse('failed', 400, null, null, $e->getMessage());
        }
    }

    public function changePassword(Request $request)
    {

    }
}

?>
