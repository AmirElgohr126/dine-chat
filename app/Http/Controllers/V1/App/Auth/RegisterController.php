<?php

namespace App\Http\Controllers\V1\App\Auth;

use Exception;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\App\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $data = $request->processedData();
            $user = User::create($data);
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $path = storeFile($photo, "users/user{$user->id}/photo", 'public');
                $user->photo = $path;
                $user->save();
            }else{
                $user->photo = 'Dafaults/User/user.png';
                $user->save();
            }
            return finalResponse('success',200,["message" => __('errors.email_send'), "user_id" => $user->id]);
        } catch (Exception $e) {
            return finalResponse('failed',500,null,null, 'something error happen ' . $e->getMessage());
        }
    }
}
