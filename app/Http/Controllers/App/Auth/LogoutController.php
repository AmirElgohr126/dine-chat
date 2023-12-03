<?php

namespace App\Http\Controllers\App\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{

    public function logout(Request $request)
    {
        auth('api')->logout();
        return finalResponse('success','200','logout successfully');
    }
}
