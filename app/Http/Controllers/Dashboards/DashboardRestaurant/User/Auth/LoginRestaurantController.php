<?php
namespace App\Http\Controllers\Dashboards\DashboardRestaurant\User\Auth;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Auth\LoginRestaurantRequest;

class LoginRestaurantController extends Controller
{
    public function loginRestaurant(LoginRestaurantRequest $request)
    {
        try {
            //  Validate user credentials from the incoming request
            $credentials = $request->validated();
            // Attempt to generate a JWT token with the provided credentials
            $token = auth('restaurant')->setTTL(env('JWT_TTL'))->attempt($credentials);
            // dd($token);
            // If token generation fails, throw an exception
            if (!$token) {
                throw new Exception('invalid credentials');
            }
            $user = auth('restaurant')->user();
            // Check if the user is verified
            // if (!$user->email_verified_at) {
            //     throw new Exception('Email not verified',405);
            // }
            // Return a successful response with the token and user information
            return finalResponse('success', 200, ["token" => $token, "user" => $user]);
        } catch (Exception $e) {
        //     // Handle exceptions, and return a failed response with a 401 status code
            return finalResponse('failed', 400, null, null, 'opps ' . $e->getMessage());
        }
    }
}


?>
