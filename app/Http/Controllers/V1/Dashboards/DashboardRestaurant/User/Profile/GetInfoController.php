<?php
namespace App\Http\Controllers\V1\Dashboards\DashboardRestaurant\User\Profile;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\DashboardAdmin\Auth\UserRestaurantDashboardResources;

class GetInfoController extends Controller
{
    public function getUser(Request $request)
    {
        try {
            $user = $request->user('restaurant');
            return finalResponse('success', 200, new UserRestaurantDashboardResources($user));
        } catch (Exception $e) {
            return finalResponse('failed', 400, null, null, $e->getMessage());
        }
    }
}
