<?php

namespace App\Http\Controllers\V1\App\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{

    public function logout(Request $request)
    {
        try {
            auth('api')->logout();
        return finalResponse('success','200',__('errors.logout_successfully'));
        } catch (\Throwable $e) {
            return finalResponse('failed',500,null,null, 'opps '.$e->getMessage());
        }

    }
}
