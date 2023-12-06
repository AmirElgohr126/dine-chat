
<?php

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\Auth\LoginController;
use App\Http\Controllers\App\Auth\LogoutController;
use App\Http\Controllers\App\Auth\RegisterController;
use App\Http\Controllers\App\Auth\VerificationController;
use App\Http\Controllers\App\ContactsList\ContactsListController;


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
Route::group(['middleware' => ['set_lang']],function(){

    Route::prefix('user')->group(function(){
        Route::group(['prefix'=>'auth'],function()
        {
            Route::post('/register',[RegisterController::class,'register']); // finshed
            Route::post('/login',[LoginController::class,'login']); // finshed
            Route::delete('/logout',[LogoutController::class,'logout'])->middleware('verified');// finshed
        });

        Route::group(['middleware' => ['auth','verified'],'prefix'=>'contactlist'],function()
        {
            Route::get('/',[ContactsListController::class,'getContactList']); //finshed
            Route::post('/',[ContactsListController::class,'postContactList']); //finshed
            Route::post('/follow',[ContactsListController::class,'followContact']); //finshed
            Route::post('/unfollow',[ContactsListController::class,'unfollowContact']); //working
            Route::post('/invite',[ContactsListController::class,'inviteContact']); //working
        });
    });



    Route::prefix('email')->group(function(){
        Route::get('verify/{id}', [VerificationController::class,'verify'])->name('verification.verify');
        Route::get('resend', [VerificationController::class,'resend'])->name('verification.resend');// finshed
        Route::get('verify', function () {
            return view('auth.verify-email');
        })->middleware('auth')->name('verification.notice');// finshed
    });

});
