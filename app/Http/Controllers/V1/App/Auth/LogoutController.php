<?php

namespace App\Http\Controllers\V1\App\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{

    /**
     * Handle user logout.
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            auth('api')->logout();
        return finalResponse('success','200',__('errors.logout_successfully'));
        } catch (\Throwable $e) {
            return finalResponse('failed',500,null,null, 'opps '.$e->getMessage());
        }

    }
}
