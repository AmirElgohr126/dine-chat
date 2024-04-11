<?php

namespace App\Http\Controllers\V1\App\Auth;

use Exception;
use App\Models\UserGhost;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\App\Auth\loginRequest;
use App\Http\Resources\V1\App\User\UserResources;
use Illuminate\Http\JsonResponse;

/**
 * Class LoginController
 * @package App\Http\Controllers\Auth
 */
class LoginController extends Controller
{
    /**
     * Handle user login.
     * @param loginRequest $request
     * @return JsonResponse
     */
    public function login(loginRequest $request): JsonResponse
    {
        try {
            //  Validate user credentials from the incoming request
            $credentials = $request->validated();
            $credentials = [
                'user_name' => $credentials['user_name'],
                'password' => $credentials['password'],
            ];
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
            $user->update([
                'device_token' => $request->device_token
            ]);
            if($user->ghost_mood == 1)
            {
                $realUserInfo = UserGhost::where('user_id',$user->id)->first();
                $user->last_name = $realUserInfo->last_name;
                $user->first_name = $realUserInfo->first_name;
                $user->photo = $realUserInfo->photo;
                $user->bio = $realUserInfo->bio ?? '';
                $user-> phone = (string) $realUserInfo->phone;
            }
            // Return a successful response with the token and user information
            return finalResponse('success',200,["token"=>$token,"user"=> new UserResources($user)]);
        } catch (Exception $e) {
            // Handle exceptions, and return a failed response with a 401 status code
            return finalResponse('failed',400,null,null, 'opps '.$e->getMessage());
        }
    }
}
