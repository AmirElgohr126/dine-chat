<?php
namespace App\Http\Controllers\V1\Dashboards\DashboardRestaurant\User\Auth;

use Exception;
use Illuminate\Http\Request;
use App\Models\RestaurantUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Dashboard\Auth\LoginRestaurantRequest;
use App\Http\Resources\V1\DashboardAdmin\Auth\UserRestaurantDashboardResources;


class LoginRestaurantController extends Controller
{
    public function loginRestaurant(LoginRestaurantRequest $request)
    {
        try {
            //  Validate user credentials from the incoming request
            $credentials = $request->validated();
            $email = RestaurantUser::where('email',$credentials['email'])->first();
            if(!$email)
            {
                throw new Exception(__('errors.email_not_found'));
            }
            // Attempt to generate a JWT token with the provided credentials
            $token = auth('restaurant')->setTTL(env('JWT_TTL'))->attempt($credentials);
            // If token generation fails, throw an exception
            if (!$token) {
                throw new Exception(__('errors.invalid_credentials'));
            }
            $user = auth('restaurant')->user();
            // Check if the user is verified
            // if (!$user->email_verified_at) {
            //     throw new Exception('Email not verified',405);
            // }
            // Return a successful response with the token and user information
            return finalResponse('success', 200, ["token" => $token, "user" => new UserRestaurantDashboardResources($user)]);
        } catch (Exception $e) {
        //     // Handle exceptions, and return a failed response with a 401 status code
            return finalResponse('failed', 400, null, null,  $e->getMessage());
        }
    }
}

