<?php
namespace App\Http\Controllers\App\Restaurant;

use Exception;

use App\Models\Restaurant;
use App\Models\UserFollower;
use Illuminate\Http\Request;
use App\Models\UserAttendance;
use Illuminate\Validation\Rule;
use App\Events\DeleteReservation;
use App\Http\Controllers\Controller;



class RestaurantController extends Controller
{

    public function usersInRestaurant(Request $request)
    {
        try {
            $restaurant = Restaurant::find($request->restaurant_id);
            if (!$restaurant)
            {
                throw new Exception(__('errors.no_restaurant'), 405);
            }
            // $oneHourAgo = Carbon::now()->subHour();
            $userAttendance = $restaurant->userAttendance()->where('created_at', '>=', now())->get();
            if (isset($userAttendance) == null) {
                return finalResponse('success', 204);
            }
            $data = [];
            foreach ($userAttendance as $attendance) {
                $userData = $attendance->users->toArray();
                $userData['x'] = $attendance->chairs->x;
                $userData['y'] = $attendance->chairs->y;
                $userData['photo'] = retriveMedia() . $userData['photo']; // Update this line
                $follow = UserFollower::where([
                    'user_id' => $request->user()->id,
                    'followed_user' => $request->user_id])->first();
                $userData['is_following'] = $follow->follow_status ?? 'not_follow' ; // Update this line

                $data[] = $userData; // Append the user data to the data array
            }
            // $userAttendance = UserAttendanceResource::collection($userAttendance);
            return finalResponse('success', 200, $data);
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
            $userAttendance = $restaurant->userAttendance()->where('created_at', '>=', now())->get();

            if (isset($userAttendance) == null) {
                return finalResponse('success', 204);
            }
            $users = [];
            foreach ($userAttendance as $attendance) {
                $userData = $attendance->users->toArray();
                $userData['x'] = $attendance->chairs->x;
                $userData['y'] = $attendance->chairs->y;
                $userData['photo'] = retriveMedia() . $userData['photo']; // Update this line
                $follow = UserFollower::where([
                    'user_id' => $request->user()->id,
                    'followed_user' => $request->user_id
                ])->first();
                $userData['is_following'] = $follow->follow_status ?? 'not_follow';
                $users[] = $userData; // Append the user data to the data array
            }
            $tables = $restaurant->tables()->get();
            $chairs = $restaurant->chairs()->get();

            return finalResponse('success', 200, ['restaurant' => $restaurant,'tables'=>$tables,'chairs'=> $chairs ,'users'=>$users]);
        } catch (Exception $e) {
            return finalResponse('success', 405, null, null, $e->getMessage());
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

    public function DeleteReservation(Request $request)
    {
        $checkReservation = UserAttendance::where('user_id', $request->user()->id)
            ->where('created_at', '>=', now())
            ->first();
        if($checkReservation)
        {
            // UpdateUserHall::dispatch($checkReservation, $checkReservation->restaurant_id);
            DeleteReservation::dispatch($checkReservation->restaurant_id, $request->user()->id);
            $checkReservation->delete();
            return finalResponse('success', 200, 'success logout form hall');
        }

        return finalResponse('error',500,'error in delete reservation');
    }
}

