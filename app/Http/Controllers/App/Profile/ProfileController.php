<?php
namespace App\Http\Controllers\App\Profile;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResources;

class ProfileController extends Controller
{
    public function photo(Request $request)
    {
        $request->validate(['photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:6144']);
        $user = $request->user();
        try {
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                if ($user->ghost_mood == 1) {
                    throw new Exception(__("errors.Can't_update_in_ghost"), 400);
                }
                $update = updateAndDeleteFile($photo, $user, 'photo', 'public', 'user', 'public');
                if ($update) {
                    return finalResponse('success', 200, __("errors.Photo_updated"));
                }
            }
            throw new Exception(__('errors.No_valid_photo'), 400);
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), $e->getMessage());
        }
    }

    public function bio(Request $request)
    {
        $request->validate(['bio' => 'nullable|string|max:101']);
        $user = $request->user();
        try {
            $bio = $request->bio;
            $user->bio = $bio;
            $user->save();
            return finalResponse('success', 200, __('errors.bio_updated'));
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), $e->getMessage());
        }
    }
    public function name(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:30',
            'last_name' => 'required|string|max:30',
        ]);
        try {
            $user = $request->user();
            $updateData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
            ];

            if ($user->ghost_mood == 1) {
                $user->ghost->update($updateData);
            } else {
                $user->update($updateData);
            }

            return finalResponse('success', 200, __('errors.Name_updated'));
        } catch (Exception $e) {
            return finalResponse('failed', 500, __('errors.Failed_to_update_name'));
        }
    }



    public function getUser(Request $request)
    {
        try {

            $user = $request->user('api');

            return finalResponse('success', 200, new UserResources($user));
        } catch (Exception $e) {
            return finalResponse('faield', 400, null, null, $e->getMessage());
        }
    }
}

?>
