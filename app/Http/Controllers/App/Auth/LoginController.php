<?php

namespace App\Http\Controllers\App\Auth;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\loginRequest;
use App\Http\Resources\User\UserResources;

/**
 * Class LoginController
 * @package App\Http\Controllers\Auth
 */
class LoginController extends Controller
{
    /**
     * Handle user login.
     *
     * @param loginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(loginRequest $request)
    {
        try {
            //  Validate user credentials from the incoming request
            $credentials = $request->validated();
            // Attempt to generate a JWT token with the provided credentials
            $token = auth('api')->setTTL(env('JWT_TTL'))->attempt($credentials);
            // If token generation fails, throw an exception
            if (!$token) {
                throw new Exception(__('errors.invalid_credentials'),405);
            }
            $user = auth('api')->user();

            // Check if the user is verified
            if (!$user->email_verified_at) {
                throw new Exception(__('errors.email_not_verified'),405);
            }

            // Return a successful response with the token and user information
            return finalResponse('success',200,["token"=>$token]);
        } catch (Exception $e) {
            // Handle exceptions, and return a failed response with a 401 status code
            return finalResponse('failed',$e->getCode(),null,null, 'opps '.$e->getMessage());
        }
    }
}
