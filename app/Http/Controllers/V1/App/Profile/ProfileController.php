<?php
namespace App\Http\Controllers\V1\App\Profile;

use Exception;
use App\Models\UserGhost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\App\User\UserResources;

class ProfileController extends Controller
{
    /**
     * Update the user's photo.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function photo(Request $request): JsonResponse
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

    /**
     * Update the user's bio.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bio(Request $request): JsonResponse
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

    /**
     * Update the user's name.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function name(Request $request): JsonResponse
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



    /**
     * Retrieve the authenticated user's profile information.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getUser(Request $request): JsonResponse
    {
        try {
            $user = $request->user('api');
            if ($user->ghost_mood == 1) {
                $realUserInfo = UserGhost::where('user_id', $user->id)->first();
                $user->last_name = $realUserInfo->last_name;
                $user->first_name = $realUserInfo->first_name;
                $user->photo = $realUserInfo->photo;
                $user->bio = $user->bio ?? '';
                $user->phone = (string) $realUserInfo->phone;
            }
            return finalResponse('success', 200, new UserResources($user));
        } catch (Exception $e) {
            return finalResponse('faield', 400, null, null, $e->getMessage());
        }
    }
}

