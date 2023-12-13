<?php
namespace App\Http\Controllers\App\Profile;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
                    throw new Exception("Can't update in ghost mood", 400);
                }
                $update = updateAndDeleteFile($photo, $user, 'photo', 'public', 'user', 'public');
                if ($update) {
                    return finalResponse('success', 200, 'Photo updated successfully');
                }
            }
            throw new Exception('No valid photo file provided.', 400);
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
            return finalResponse('success', 200, 'bio updated successfully');
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

            return finalResponse('success', 200, 'Name updated successfully');
        } catch (Exception $e) {
            return finalResponse('failed', 500, 'Failed to update name');
        }
    }
}

?>
