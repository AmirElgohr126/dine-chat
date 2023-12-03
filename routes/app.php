
<?php

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\Auth\LoginController;
use App\Http\Controllers\App\Auth\LogoutController;
use App\Http\Controllers\App\Auth\RegisterController;
use App\Http\Controllers\App\Auth\VerificationController;


/*
|--------------------------------------------------------------------------
| API app Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::prefix('auth')->group(function(){
    Route::post('register',[RegisterController::class,'register']);
    Route::post('login',[LoginController::class,'login']);
    Route::delete('logout',[LogoutController::class,'logout'])->middleware('verified');


});
Route::prefix('email')->group(function(){
    Route::get('verify/{id}', [VerificationController::class,'verify'])->name('verification.verify');
    Route::get('resend', [VerificationController::class,'resend'])->name('verification.resend');
    Route::get('verify', function () {
        return view('auth.verify-email');
    })->middleware('auth')->name('verification.notice');
});

