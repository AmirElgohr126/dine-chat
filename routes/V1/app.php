<?php


use App\Http\Controllers\V1\App\About\AboutController;
use App\Http\Controllers\V1\App\Auth\ForgetPasswordController;
use App\Http\Controllers\V1\App\Auth\LoginController;
use App\Http\Controllers\V1\App\Auth\LogoutController;
use App\Http\Controllers\V1\App\Auth\RegisterController;
use App\Http\Controllers\V1\App\Auth\VerificationController;
use App\Http\Controllers\V1\App\Chat\ChatController;
use App\Http\Controllers\V1\App\Chat\MessageController;
use App\Http\Controllers\V1\App\ContactsList\ContactsListController;
use App\Http\Controllers\V1\App\Games\GameController;
use App\Http\Controllers\V1\App\Games\XOgame\XoController;
use App\Http\Controllers\V1\App\Map\GetMapsOfRestaurantController;
use App\Http\Controllers\V1\App\Notifications\NotificationController;
use App\Http\Controllers\V1\App\Profile\ProfileController;
use App\Http\Controllers\V1\App\Ratings\FoodRatingController;
use App\Http\Controllers\V1\App\Ratings\RestaurantRatingController;
use App\Http\Controllers\V1\App\Reservation\ReservationController;
use App\Http\Controllers\V1\App\Restaurant\RestaurantController;
use App\Http\Controllers\V1\App\Settings\SettingsController;
use Illuminate\Support\Facades\Route;


Route::middleware('set_lang')->group(function () {


    Route::group(['prefix' => 'about'], function () {
        Route::get('/policy', [AboutController::class, 'privacyPolicy']); // finished
        Route::get('/terms', [AboutController::class, 'termsConditions']); // finished
        Route::get('/aboutus', [AboutController::class, 'aboutUs']); // finished
    });

    Route::group(['prefix' => 'user/auth'], function () {
        Route::post('/register', [RegisterController::class, 'register']); // finished
        Route::post('/login', [LoginController::class, 'login']); // finished
        Route::delete('/logout', [LogoutController::class, 'logout']); // finished
        Route::post('/forgot-password', [ForgetPasswordController::class, 'forgetPasswordSendOtp']); // finished
        Route::post('verify-otp', [ForgetPasswordController::class, 'verifyOtp']); // finished
        Route::post('/reset-password', [ForgetPasswordController::class, 'resetPassword']); // finished
    });
    // =====================================================================================
    Route::prefix('email')->group(function () {
        Route::get('verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify'); // finished
        Route::post('resend', [VerificationController::class, 'resend'])->name('verification.resend'); // finished
        Route::get('verify', function () {
            return view('auth.verify-email');
        })->middleware('auth')->name('verification.notice'); // finished
    });

    Route::group(['middleware' => ['auth:api', 'verified']], function () {
        // =====================================================================================
        Route::group(['prefix' => 'contactlist'], function () {
            Route::post('/add', [ContactsListController::class, 'postContactList']); //finished
            Route::get('/', [ContactsListController::class, 'getContactList']); //finished
            Route::post('/follow', [ContactsListController::class, 'followContact']); //finished
            Route::post('/unfollow', [ContactsListController::class, 'unfollowContact']); // finished
            Route::post('/invite', [ContactsListController::class, 'inviteContact']); // finished
        });
        // =====================================================================================
        Route::post('parameter/validate', [ReservationController::class, 'reservationFactory']); // finished
        // =====================================================================================
        Route::group(['prefix' => 'restaurant'], function () {
            Route::get('map', [GetMapsOfRestaurantController::class, 'closestRestaurants']); // finished
            Route::get('user', [RestaurantController::class, 'usersInRestaurant']); // finished
            Route::get('assets', [RestaurantController::class, 'getTablesAndChairs']); // finished
            Route::post('/delete', [RestaurantController::class, 'deleteReservation']); // finished
            // =====================================================================================

        });
        // =====================================================================================
        Route::group(['prefix' => 'notification'], function () {
            Route::get('/', [NotificationController::class, 'getNotifications']); // finished
        });
        // =====================================================================================
        Route::group(['prefix' => 'rating'],function (){
            Route::get('/', [FoodRatingController::class, 'getFoodOfRestaurant'])->middleware('check_reservation'); // finished
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
            Route::post('inbox/accept',[ChatController::class,'acceptInboxChat']); // finished
            Route::delete('inbox/reject',[ChatController::class,'rejectInboxChat']); // finished
            Route::get('inbox/list',[ChatController::class,'listInboxChat']); // finished
            // ===========================
            Route::get('/{id}', [MessageController::class, 'getMessages'])
                ->where('id', '[0-9]+'); // finished
            Route::post('{id}/send', [MessageController::class, 'sendMessage'])
                ->where('id', '[0-9]+')->middleware('chat_baned'); // finished
            Route::post('{id}/message/{id_message}/update', [MessageController::class, 'updateMessage'])
                ->where('id', '[0-9]+')->where('id_message','[0-9]+'); // finished
            Route::delete('{id}/message/{id_message}/delete', [MessageController::class, 'deleteMessage'])
                ->where('id', '[0-9]+')->where('id_message', '[0-9]+'); // finished

            // routes ban and unban
            Route::post('ban', [ChatController::class, 'banChat']); // finished
            Route::post('unban', [ChatController::class, 'unbanChat']); // finished
            Route::post('report', [ChatController::class, 'reportChat']); // finished
        });
        // =====================================================================================
        Route::group(['prefix' => 'game','middleware'=>'check_reservation'], function () {
                Route::post('request/send', [GameController::class, 'requestToPlay']); // finished
                Route::post('request/cancel', [GameController::class, 'cancelRequest']); // finished
                Route::get('request/list', [GameController::class, 'listRequests']); // finished
                // ==========================
                Route::post('inbox/accept', [GameController::class, 'AcceptInvite']); // finished
                Route::post('inbox/reject', [GameController::class, 'cancelInvite']); // finished
                Route::get('inbox/list', [GameController::class, 'listInvites']); // finished

                // =========================== XO ==============================
                Route::group(['prefix' => 'xo'], function () {
                Route::get('/', [XoController::class, 'getBoard']);
                Route::post('{game}/move', [XoController::class, 'move'])
                ->where('game', '[0-9]+'); // finished
            });
        });
    });
});
