<?php

namespace App\Http\Controllers\App\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Resources\User\UserResources;
use App\Http\Requests\Auth\RegisterRequest;

class RegisterController extends Controller
{
    /**
     * Handle the user registration process.
     *
     * @param  \App\Http\Requests\Auth\RegisterRequest  $request
     * @return \Illuminate\Http\JsonResponse;
     */
    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->processedData();
            $user = User::create($data); // Create a new user
            return finalResponse('success',200,__('errors.email_send'));
        } catch (Exception $e) {
            // handle it appropriately
            return finalResponse('faild',500,null,null, 'something error happen ' . $e->getMessage());
        }
    }
}
