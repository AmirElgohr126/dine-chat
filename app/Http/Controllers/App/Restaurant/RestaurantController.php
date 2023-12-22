<?php
namespace App\Http\Controllers\App\Restaurant;

use Exception;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Restaurant\UserAttendanceResource;
use App\Models\UserFollower;
use Illuminate\Validation\Rule;


class RestaurantController extends Controller
{

    public function usersInRestaurant(Request $request)
    {
        try {
            $restaurant = Restaurant::find($request->restaurant_id);
            if (!$restaurant) {
                throw new Exception(__('errors.no_restaurant'), 405);
            }
            $userAttendance = $restaurant->userAttendance()->with(['users', 'tables', 'chairs'])->get();
            if (isset($userAttendance) == null) {
                return finalResponse('success', 204);
            }
            $userAttendance = UserAttendanceResource::collection($userAttendance);
            return finalResponse('success', 200, $userAttendance);
        } catch (Exception $e) {
            return finalResponse('failed', 500, null, $e->getMessage());
        }
    }

    public function getTablesAndChairs(Request $request)
    {
        try {
            $restaurant = Restaurant::find($request->restaurant_id);
            if (!$restaurant) {
                throw new Exception(__('errors.no_restaurant'), 405);
            }
            $assetRestaurant = $restaurant->tables()->with('chairs')->get();
            return finalResponse('success', 200, $assetRestaurant);
        } catch (Exception $e) {
            return finalResponse('success', $e->getCode(), null, null, $e->getMessage());
        }
    }

    public function followUser(Request $request)
    {
        $request->validate([
            'user_id' => ['required',
                Rule::exists('users', 'id')->where(function ($query) use ($request) {
                    $query->where('id', '!=', $request->user()->id);
                }),
            ]
        ]);
        try {
            // Check if the user is already following the target user
            $isFollowing = UserFollower::where('user_id', $request->user()->id)->where('followed_user', $request->user_id)->exists();
            if (!$isFollowing) {
                // If not already following, create a new UserFollower record
                $follow = UserFollower::create([
                    'user_id' => $request->user()->id,
                    'followed_user' => $request->user_id,
                    'follow_status' => 'follow',
                    ]);

                return finalResponse('success', 200, __('errors.you_follow'));
            } else {
                throw new Exception(__('errors.already_followed'), 400);
            }
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), null, null, $e->getMessage());
        }
    }
}

?>
