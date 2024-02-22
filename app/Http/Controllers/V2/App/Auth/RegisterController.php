<?php

namespace App\Http\Controllers\V2\App\Auth;

use Exception;
use App\Models\User;
use App\Http\Controllers\Controller;

use App\Http\Requests\V2\App\Auth\RegisterRequest;

class RegisterController extends Controller
{
    /**
     * Handle the user registration process.
     *
     * @param  RegisterRequest  $request
     * @return \Illuminate\Http\JsonResponse;
     */
    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->processedData();
            $user = User::create($data); // Create a new user
            // Process and store the user's photo
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $path = storeFile($photo, "users/user{$user->id}/photo", 'public');
                $user->photo = $path;
                $user->save();
            }
            return finalResponse('success',200,["message" => __('errors.email_send'), "user_id" => $user->id]);
        } catch (Exception $e) {
            // handle it appropriately
            return finalResponse('faild',500,null,null, 'something error happen ' . $e->getMessage());
        }
    }
}
