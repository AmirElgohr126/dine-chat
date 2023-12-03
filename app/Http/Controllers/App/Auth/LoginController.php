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
                throw new Exception('Invalid credentials');
            }
            $user = auth('api')->user();
            // Return a successful response with the token and user information
            return finalResponse('success',200,["token"=>$token,"user"=> new UserResources($user)]);
        } catch (Exception $e) {
            // Handle exceptions, and return a failed response with a 401 status code
            return finalResponse('failed',401,null,null, 'opps'.$e->getMessage());
        }
    }
}
