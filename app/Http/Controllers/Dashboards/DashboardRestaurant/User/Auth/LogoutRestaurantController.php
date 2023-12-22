<?php
namespace App\Http\Controllers\Dashboards\DashboardRestaurant\User\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogoutRestaurantController extends Controller
{
    public function logoutRestaurant(Request $request)
    {
        try {
            auth('restaurant')->logout();
            return finalResponse('success', '200',__('errors.logout_successfully'));
        } catch (\Throwable $e) {
            return finalResponse('failed', 500, null, null, 'opps ' . $e->getMessage());
        }

    }
}

?>
