<?php
namespace App\Http\Controllers\V1\Dashboards\DashboardRestaurant\Waiter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\App\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WaiterController extends Controller
{
    /**
     * Add a new waiter to the restaurant.
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function addWaiter(RegisterRequest $request): JsonResponse
    {
        $validated = $request->processedData();
        $restaurant = auth()->user('restaurant');
        $user = User::create($validated);
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $path = storeFile($photo, "users/user{$user->id}/photo", 'public');
            $user->photo = $path;
            $user->save();
        }else{
            $user->photo = 'Dafaults/User/user.png';
            $user->save();
        }
        $restaurant->waiters()->attach($user->id);
        return finalResponse('success',200,('errors.email_send'));
    }


    /**
     * Delete a waiter from the restaurant.
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteWaiter(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|numeric|exists:users,id',
        ]);
        $restaurant = auth()->user('restaurant');
        $user = User::find($request->id);
        if (!$restaurant->waiters()->find($user->id)) {
            return finalResponse('error', 404, ('errors.The_user_is_not_a_waiter_in_this_restaurant'));
        }
        $restaurant->waiters()->detach($user->id);
        $user->delete();
        return finalResponse('success',200,('errors.waiter_deleted'));
    }


    /**
     * Get one waiter from the restaurant.
     * @param Request $request
     * @return JsonResponse
     */
    public function getOneWaiter(Request $request): JsonResponse
    {
        $restaurant = auth()->user('restaurant');
        $user = $restaurant->waiters()->find($request->id);
        if (!$user) {
            return finalResponse('error', 404, ('errors.waiter_not_found'));
        }
        return finalResponse('success',200,('errors.waiter_added'),$user);
    }


    /**
     * Get all waiters from the restaurant.
     * @param Request $request
     * @return JsonResponse
     */
    public function getWaiters(Request $request): JsonResponse
    {
        $restaurant = auth()->user('restaurant');
        $waiters = $restaurant->waiters;
        return finalResponse('success',200,('errors.waiter_added'),$waiters);
    }


    /**
     * Deactivate a waiter from the restaurant.
     * @param Request $request
     * @return JsonResponse
     */
    public function deactivateWaiter(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|numeric|exists:users,id',
        ]);
        $restaurant = auth()->user('restaurant');
        $user = User::find($request->id);
        if (!$restaurant->waiters()->find($user->id)) {
            return finalResponse('error', 404, ('errors.The_user_is_not_a_waiter_in_this_restaurant'));
        }
        $restaurant->waiters()->updateExistingPivot($user->id, ['status' => 0]);
        return finalResponse('success',200,('errors.waiter_deactivated'));
    }



    /**
     * Activate a waiter from the restaurant.
     * @param Request $request
     * @return JsonResponse
     */
    public function activateWaiter(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|numeric|exists:users,id',
        ]);
        $restaurant = auth()->user('restaurant');
        $user = User::find($request->id);
        if (!$restaurant->waiters()->find($user->id)) {
            return finalResponse('error', 404, ('errors.The_user_is_not_a_waiter_in_this_restaurant'));
        }
        $restaurant->waiters()->updateExistingPivot($user->id, ['status' => 1]);
        return finalResponse('success',200,('errors.waiter_deactivated'));
    }
}
