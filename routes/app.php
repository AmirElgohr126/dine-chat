<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\Nfc\NfcController;
use App\Http\Controllers\App\Chat\ChatController;
use App\Http\Controllers\App\Auth\LoginController;
use App\Http\Controllers\App\About\AboutController;
use App\Http\Controllers\App\Auth\LogoutController;
use App\Http\Controllers\App\Chat\MessageController;
use App\Http\Controllers\App\Auth\RegisterController;
use App\Http\Controllers\App\Profile\ProfileController;
use App\Http\Controllers\App\Auth\VerificationController;
use App\Http\Controllers\App\Settings\SettingsController;
use App\Http\Controllers\App\Ratings\FoodRatingController;
use App\Http\Controllers\App\Restaurant\RestaurantController;
use App\Http\Controllers\App\Ratings\RestaurantRatingController;
use App\Http\Controllers\App\ContactsList\ContactsListController;
use App\Http\Controllers\App\Notifications\NotificationController;
use App\Http\Controllers\App\Restaurant\GetMapsOfRestaurantController;

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

Route::middleware('set_lang')->group(function () {

    Route::group(['prefix' => 'user/auth'], function () {
        Route::post('/register', [RegisterController::class, 'register']); // finished
        Route::post('/login', [LoginController::class, 'login']); // finished
        Route::delete('/logout', [LogoutController::class, 'logout']); // finished
    });
    // =====================================================================================
    Route::prefix('email')->group(function () {
        Route::get('verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
        Route::post('resend', [VerificationController::class, 'resend'])->name('verification.resend'); // finished
        Route::get('verify', function () {
            return view('auth.verify-email');
        })->middleware('auth')->name('verification.notice'); // finished
    });

    Route::group(['middleware' => ['auth:api', 'verified']], function () {

        Route::group(['prefix' => 'contactlist'], function () {
            Route::post('/add', [ContactsListController::class, 'postContactList']); //finished
            Route::get('/', [ContactsListController::class, 'getContactList']); //finished
            Route::post('/follow', [ContactsListController::class, 'followContact']); //finished
            Route::post('/unfollow', [ContactsListController::class, 'unfollowContact']); // finished
            Route::post('/invite', [ContactsListController::class, 'inviteContact']); // finished
        });
        // =====================================================================================
        Route::post('nfc/vaildate', [NfcController::class, 'VaildateNfcParameter']); // finished
        // =====================================================================================
        Route::group(['prefix' => 'restaurant'], function () {
            Route::get('map', [GetMapsOfRestaurantController::class, 'closestRestaurants']); // finished
            Route::get('user', [RestaurantController::class, 'usersInRestaurant']); // finished
            Route::get('assets', [RestaurantController::class, 'getTablesAndChairs']); // finished
            // =====================================================================================

        });
        Route::group(['prefix' => 'notification'], function () {
            Route::get('/', [NotificationController::class, 'getNotifications']); // finished
        });
        // =====================================================================================
        Route::group(['prefix' => 'rating'],function (){
            Route::get('/', [FoodRatingController::class, 'getFoodOfrestaurant'])->middleware('check_reservation'); // finished
            Route::post('add', [FoodRatingController::class, 'makeRatingForFood'])->middleware('check_reservation');
            Route::get('restaurant',[RestaurantRatingController::class,'restaurantsRating']); // finished
            Route::get('food',[FoodRatingController::class,'foodsRating']); // finished
        });
        // =====================================================================================
        Route::group(['prefix' => 'settings'], function () {
            Route::post('email', [SettingsController::class, 'changeEmail']); // finished
            Route::post('password', [SettingsController::class, 'changePassword']); // finished
            Route::post('ghost', [SettingsController::class, 'changeToGhost']); // finished
            Route::post('notification/mute', [SettingsController::class, 'muteNotifications']); // finished
        });
        // =====================================================================================
        Route::group(['prefix' => 'profile'], function () {
            Route::get('user', [ProfileController::class, 'getUser']); // finished
            Route::post('photo', [ProfileController::class, 'photo']); // finished
            Route::post('name', [ProfileController::class, 'name']); // finished
            Route::post('bio', [ProfileController::class, 'bio']); // finished
        });
        // =====================================================================================
        Route::group(['prefix' => 'about'], function () {
            Route::get('/policy', [AboutController::class, 'privacyPolicy']); // finished
            Route::get('/terms', [AboutController::class, 'termsConditions']); // finished
            Route::get('/aboutus', [AboutController::class, 'aboutUs']); // finished
        });
        // =====================================================================================
        Route::post('user/follow',[RestaurantController::class,'followUser']); // finished
        // =====================================================================================

        Route::group(['prefix' => 'chat','middleware'=>'check_reservation'], function () {
            Route::get('/', [ChatController::class, 'getChats']); // finished .. get chats with only one message and info of user that i send to you
            // ==========================
            Route::post('request/send',[ChatController::class,'sendRequestChat']); // finished
            Route::post('request/cancel',[ChatController::class,'cancelRequestChat']); // finished
            Route::get('request/list',[ChatController::class,'listRequestsChat']); // finished
            // ==========================
            Route::post('inbox/accept',[ChatController::class,'AcceptinboxChat']); // finished
            Route::delete('inbox/reject',[ChatController::class,'RejectinboxChat']); // finished
            Route::get('inbox/list',[ChatController::class,'listInboxChat']); // finished
            // ===========================
            Route::get('/{id}', [MessageController::class, 'getMessages'])
                ->where('id', '[0-9]+');
            Route::post('{id}/send', [MessageController::class, 'sendMessage'])
                ->where('id', '[0-9]+');
            Route::post('{id}/message/{id_message}/update', [MessageController::class, 'updateMessage'])
                ->where('id', '[0-9]+')->where('id_message','[0-9]+');
            Route::delete('{id}/message/{id_message}/delete', [MessageController::class, 'deleteMessage'])
                ->where('id', '[0-9]+')->where('id_message', '[0-9]+');
        });

    });
});

