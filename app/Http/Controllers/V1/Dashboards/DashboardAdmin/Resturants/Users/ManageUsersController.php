<?php
namespace App\Http\Controllers\V1\Dashboards\DashboardAdmin\Resturants\Users;

use Illuminate\Http\Request;
use App\Models\RestaurantUser;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\V1\DashboardAdmin\Auth\UserRestaurantDashboardResources;

class ManageUsersController extends Controller
{

    /**
     * list One Restauran tUsers
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function listOneRestaurantUsers(Request $request)
    {
        $per_page = $request->per_page ?? 10;
        $request->merge(['id' => $request->id]);
        $request->validate([
            'id' => 'exists:restaurants,id',
        ]);
        $id = $request->id;
        $users = RestaurantUser::where('restaurant_id', $id)->paginate($per_page);
        if (!$users) {
            return finalResponse('failed', 400, null, null, 'no user found');
        }
        $pagnationResponse = pagnationResponse($users);
        $users = UserRestaurantDashboardResources::collection($users);
        return finalResponse('success', 200, $users, $pagnationResponse);

    }


    /**
     * create RestaurantUsers
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function addRestaurantUsers(Request $request)
    {
        $validated = $request->validate([
            'user_name' => ['required', 'unique:restaurant_users,user_name'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:restaurant_users,email'],
            'photo' => 'nullable|image|max:2048',
            'phone' => ['required', 'numeric','unique:restaurant_users,phone'],
            'password' => [
                'required',
                'string',
                'min:8',                    // Minimum length
                'regex:/^(?=.*[A-Z])/',     // At least one uppercase letter
                'regex:/^(?=.*[a-z])/',     // At least one lowercase letter
                'regex:/^(?=.*[0-9])/',     // At least one digit
                'regex:/^(?=.*[@$!%*?&])/'
            ], // At least one special character
            'status' => ['required', 'in:active,inactive'],
            'restaurant_id' => ['required', 'exists:restaurants,id'],
            'start_subscription' => ['required', 'date'],
            'expire_subscription' => ['required','date','after:' . now()->toDateTimeString()],
        ]);

        $restaurantUser = RestaurantUser::create([
            'name' => $validated['name'],
            'user_name' => $validated['user_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'status' => $validated['status'],
            'restaurant_id' => $validated['restaurant_id'],
            'start_subscription' => $validated['start_subscription'],
            'expire_subscription' => $validated['expire_subscription'],
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $path = storeFile($photo, "restaurants/restaurant{$restaurantUser->restaurant_id}/users", 'public');
            $restaurantUser->photo = $path;
            $restaurantUser->save();
        } else {
            $path = 'Dafaults/RestaurantUser/restaurant_user.png';
            $restaurantUser->photo = $path;
            $restaurantUser->save();
        }
        return finalResponse('success', 200, 'data added successfully');
    }


    /**
     * update RestaurantUsers
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function updateRestaurantUsers(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:restaurant_users,id',
            'name' => ['required', 'string', 'max:255'],
            'user_name' => "required|unique:restaurant_users,user_name,$request->user_id",
            'email' => "required|email|unique:restaurant_users,email,$request->user_id",
            'photo' => 'nullable|image|max:2048',
            'phone' => "required|numeric|unique:restaurant_users,phone,$request->user_id",
            'password' => "nullable|string|min:8|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[@$!%*?&]).*$/",
            'status' => 'required|in:active,inactive',
            'restaurant_id' => 'required|numeric|exists:restaurants,id',
            'start_subscription' => ['required', 'date'],
            'expire_subscription' => ['required', 'date', 'after:' . now()->toDateTimeString()],
        ]);

        $restaurantUser = RestaurantUser::findOrFail($validated['user_id']);

        $restaurantUser->update([
            "user_name" => $validated['user_name'],
            "name" => $validated['name'],
            "email" => $validated['email'],
            "phone" => $validated['phone'],
            "status" => $validated['status'],
            "restaurant_id" => $validated['restaurant_id'],
            'start_subscription' => $validated['start_subscription'],
            'expire_subscription' => $validated['expire_subscription'],
        ]);

        if ($request->hasFile('photo')) {
            $new = $request->file('photo');
            $old = $restaurantUser->photo;
            $path = storeFile($new, "restaurants/restaurant{$restaurantUser->restaurant_id}/users", 'public');
            $restaurantUser->photo = $path;
            $restaurantUser->save();
            if ($old) {
                Storage::disk('public')->delete($old);
            }
        }
        return finalResponse('success', 200, 'data updated successfully');
    }


    /**
     * delete Restaurant Users
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function deleteRestaurantUsers(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:restaurant_users,id',
            'restaurant_id' => 'required|numeric|exists:restaurants,id',
        ]);

        $restaurantUser = RestaurantUser::where('id', $validated['user_id'])->where('restaurant_id', $validated['restaurant_id'])
            ->firstOrFail();

        if ($restaurantUser->photo) {
            $oldPhoto = $restaurantUser->photo;
            Storage::disk('public')->delete($oldPhoto);
        }
        $restaurantUser->delete();
        return finalResponse('success', 200, 'Restaurant user deleted successfully.');

    }


}
