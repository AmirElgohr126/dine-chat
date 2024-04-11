<?php

use App\Http\Controllers\V1\Dashboards\DashboardRestaurant\Waiter\WaiterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Dashboards\DashboardRestaurant\Foods\FoodController;
use App\Http\Controllers\V1\Dashboards\DashboardRestaurant\Assest\RestaurantAssest;
use App\Http\Controllers\V1\Dashboards\DashboardRestaurant\Foods\FoodMenuController;
use App\Http\Controllers\V1\Dashboards\DashboardRestaurant\Tickets\TicketsController;
use App\Http\Controllers\V1\Dashboards\DashboardRestaurant\User\Profile\GetInfoController;
use App\Http\Controllers\V1\Dashboards\DashboardRestaurant\Statistics\StatisticsController;
use App\Http\Controllers\V1\Dashboards\DashboardRestaurant\User\Auth\LoginRestaurantController;
use App\Http\Controllers\V1\Dashboards\DashboardRestaurant\Notifications\NotificationController;
use App\Http\Controllers\V1\Dashboards\DashboardRestaurant\User\Auth\LogoutRestaurantController;
use App\Http\Controllers\V1\Dashboards\DashboardRestaurant\User\Profile\ProfileRestaurantController;
use App\Http\Controllers\V1\Dashboards\DashboardRestaurant\User\Auth\ResetPasswordRestaurantController;


Route::middleware('set_lang')->group(function () {
    Route::group(['prefix' => 'user/auth'], function () {
        Route::post('/login', [LoginRestaurantController::class, 'loginRestaurant']); // finished
        Route::post('/sendcode', [ResetPasswordRestaurantController::class, 'sendOTP']); // finished
        Route::post('/changepassword', [ResetPasswordRestaurantController::class, 'resetpassword']); // finished
        Route::delete('/logout', [LogoutRestaurantController::class, 'logoutRestaurant']); // finished
    });
    Route::group(['prefix' => 'food', 'middleware' => ['auth:restaurant']], function () {
        Route::get('get', [FoodMenuController::class, 'menu']); // finished
        Route::post('add', [FoodController::class, 'addFood']); // finished
        Route::post('update', [FoodController::class, 'updateFood']); // finished
        Route::post('delete', [FoodController::class, 'deleteFood']); // finished
        Route::get('/{id}', [FoodController::class, 'getOneFood'])
            ->where('id', '[0-9]+'); // finished
    });
    Route::group(['prefix' => 'waiter', 'middleware' => ['auth:restaurant']], function () {
        Route::post('add', [WaiterController::class, 'addWaiter']); // finished
        Route::post('delete', [WaiterController::class, 'deleteWaiter']); // finished
        Route::post('deactivate', [WaiterController::class, 'deactivateWaiter']); // finished
        Route::post('activate', [WaiterController::class, 'activateWaiter']); // finished
        Route::get('/list', [WaiterController::class, 'getWaiters']); // finished
        Route::get('/', [WaiterController::class, 'getOneWaiter'])
            ->where('id', '[0-9]+'); // finished
    });
    Route::group(['prefix' => 'profile', 'middleware' => ['auth:restaurant']], function () {
        Route::post('update', [ProfileRestaurantController::class, 'updateProfile']); // finished
        Route::post('/password/update', [ProfileRestaurantController::class, 'changePassword']); // finished
        Route::get('', [GetInfoController::class, 'getUser']); // finished
    });
    Route::group(['prefix' => 'notification', 'middleware' => ['auth:restaurant']], function () {
        Route::get('/', [NotificationController::class, 'listNotification']); // finished
        Route::post('create', [NotificationController::class, 'createNotification']); // finished
        Route::post('delete', [NotificationController::class, 'deleteNotification']); // finished
        Route::post('send', [NotificationController::class, 'sendNotificationNow']); // finished
    });
    Route::group(['prefix' => 'tables', 'middleware' => ['auth:restaurant']], function () {
        Route::post('/create', [RestaurantAssest::class, 'createAssets']); // finished
        Route::get('/list', [RestaurantAssest::class, 'listAssets']); // finished
    });
    Route::group(['prefix' => 'tickets', 'middleware' => ['auth:restaurant']], function () {
        Route::get('/list', [TicketsController::class, 'listTiketsForUser']); // finished
        Route::post('create', [TicketsController::class, 'create']); // finished
        Route::post('message/store', [TicketsController::class, 'storeMessage']); // finished
        Route::get('messages', [TicketsController::class, 'getMessagesForTicket']); // finished
    });
    Route::group(['middleware' => ['auth:restaurant']], function () {
        Route::get('/review', [StatisticsController::class, 'restaurantReviewFromFive']);
        Route::get('/clients', [StatisticsController::class, 'getRestaurantClients']);
        Route::get('/review-count', [StatisticsController::class, 'getRestaurantReveiwCount']);
        Route::get('/subscription-stats', [StatisticsController::class, 'subscription_statistics']);
        Route::get('/food-rating', [StatisticsController::class, 'recentTransitions']);
    });
});
