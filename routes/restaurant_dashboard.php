
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboards\DashboardRestaurant\Foods\FoodController;
use App\Http\Controllers\Dashboards\DashboardRestaurant\Foods\FoodMenuController;
use App\Http\Controllers\Dashboards\DashboardRestaurant\User\Auth\AddRestaurantOrder;
use App\Http\Controllers\Dashboards\DashboardRestaurant\User\Auth\LoginRestaurantController;
use App\Http\Controllers\Dashboards\DashboardRestaurant\User\Auth\LogoutRestaurantController;
use App\Http\Controllers\Dashboards\DashboardRestaurant\User\Profile\ProfileRestaurantController;
use App\Http\Controllers\Dashboards\DashboardRestaurant\User\Auth\ResetPasswordRestaurantController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::middleware('set_lang')->group(function () {

    Route::group(['prefix' => 'user/auth'], function () {
        Route::post('/login', [LoginRestaurantController::class, 'loginRestaurant']); // finished
        Route::post('/sendcode', [ResetPasswordRestaurantController::class, 'sendOTP']); // finished
        Route::post('/changepassword', [ResetPasswordRestaurantController::class, 'resetpassword']); // finished
        Route::delete('/logout', [LogoutRestaurantController::class, 'logoutRestaurant']); // finished
    });
    Route::post('user/restaurant/add', [AddRestaurantOrder::class, 'OrderAddRestaurant']); // finished

    Route::group(['prefix'=>'food','middleware'=>['auth:restaurant']],function()
    {
        Route::post('add',[FoodController::class,'addFood']);
        Route::post('update',[FoodController::class,'updateFood']);
        Route::post('delete',[FoodController::class,'deleteFood']);
        Route::get('get',[FoodMenuController::class,'menu']);
    });
    Route::group(['prefix'=>'profile','middleware'=>['auth:restaurant']],function()
    {
        Route::post('update',[ProfileRestaurantController::class,'updateProfile']);
        Route::post('/password/update',[ProfileRestaurantController::class,'changePassword']);
    });

});




