<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V2\Dashboards\DashboardAdmin\Auth\AuthAdminController;
use App\Http\Controllers\V2\Dashboards\DashboardAdmin\Auth\ApplcationController;
use App\Http\Controllers\V2\Dashboards\DashboardAdmin\Statistics\StatisticsController;
use App\Http\Controllers\V2\Dashboards\DashboardAdmin\Resturants\Users\ManageUsersController;
use App\Http\Controllers\V2\Dashboards\DashboardAdmin\Settings\AboutSettings\AboutController;
use App\Http\Controllers\V2\Dashboards\DashboardAdmin\Settings\ChatSettings\RestrictionsController;
use App\Http\Controllers\V2\Dashboards\DashboardAdmin\PublicPlaces\PublicPlaces\PublicPlacesContoller;
use App\Http\Controllers\V2\Dashboards\DashboardAdmin\PublicPlaces\QrCodeForPlaces\QrPlacesController;
use App\Http\Controllers\V2\Dashboards\DashboardAdmin\Settings\ChatSettings\QuestionableChatController;
use App\Http\Controllers\V2\Dashboards\DashboardAdmin\Resturants\Restaurants\ManageRestaurantsController;
use App\Http\Controllers\V2\Dashboards\DashboardAdmin\Settings\PublicSettings\DashboardSettingsController;
use App\Http\Controllers\V2\Dashboards\DashboardAdmin\Resturants\Restaurants\RestaurantSubscrptionController;
use App\Http\Controllers\V2\Dashboards\DashboardAdmin\Resturants\QrCodeForRestaurant\GenerateUrlAndQrController;
use App\Http\Controllers\V2\Dashboards\DashboardAdmin\SubscriptionPackages\SubscriptionPackagesNotifications\SubscriptionNotificationsController;
use App\Http\Controllers\V2\Dashboards\DashboardAdmin\SubscriptionPackages\SubscriptionPackagesRestaurants\SubscriptionPackageRestaurantsController;


Route::middleware('set_lang')->group(function () {
    Route::group(['prefix' => 'v1'], function () {

        Route::group(['prefix' => 'auth'], function () {
            Route::post('/register', [AuthAdminController::class, 'registerDashboard']); // finished
            Route::post('/login', [AuthAdminController::class, 'loginDashboard']); // finished
            Route::delete('/logout', [AuthAdminController::class, 'logout']); // finished
            Route::post('/reset-password', [AuthAdminController::class, 'resetPasswordDashboard']);  // finished
            Route::post('/send-mail', [AuthAdminController::class, 'sendMail']);  // finished
        });


        Route::group(['middleware' => 'auth:admin'], function () {

            Route::group(['prefix' => 'application'], function () {
                // add applications
                Route::post('add', [ApplcationController::class, 'OrderAddRestaurant']); // finished
                // list applications
                Route::get('/', [ApplcationController::class, 'listApplacation']); // finished
                // accept an application
                Route::post('{applicationId}/accept', [ApplcationController::class, 'acceptApplacation'])
                ->where('applicationId', '[0-9]+');  // finished
                // reject an application
                Route::post('{applicationId}/reject', [ApplcationController::class, 'rejectApplacation'])
                ->where('applicationId', '[0-9]+');  // finished
            });

            Route::group(['prefix' => 'public-places'], function () {
                // List all public places
                Route::get('/', [PublicPlacesContoller::class, 'listPublicPlace']);  // finished

                // Create a new public place
                Route::post('/add', [PublicPlacesContoller::class, 'addPublicPlace']); //finished

                // Update a specific public place
                Route::put('/{id}/update', [PublicPlacesContoller::class, 'updatePublicPlace']) //finished
                ->where('id', '[0-9]+');

                // Delete a specific public place
                Route::delete('{id}/delete', [PublicPlacesContoller::class, 'deletePublicPlace']) //finished
                ->where('id', '[0-9]+');
            });

            Route::group(['prefix' => 'qr-places'], function () {
                // Generate a QR code for public places
                Route::post('/generate', [QrPlacesController::class, 'generateQrCode']); // finished

                // Update a QR code for public places
                Route::get('/{id}', [QrPlacesController::class, 'getQrCode'])
                ->where('id', '[0-9]+'); // finished

                // Delete a QR code for public places
                Route::post('/delete', [QrPlacesController::class, 'deleteQrCode']); // finished
            });

            Route::group(['prefix' => 'restaurants'], function () {
                // List restaurants
                Route::get('/', [ManageRestaurantsController::class, 'listRestaurant']); // finished

                // Create a new restaurant
                Route::post('/add', [ManageRestaurantsController::class, 'addRestaurant']); // finished

                // Update a restaurant
                Route::post('{id}/update', [ManageRestaurantsController::class, 'updateRestaurant'])
                ->where('id', '[0-9]+'); // finished

                // Delete a restaurant
                Route::delete('{id}/delete', [ManageRestaurantsController::class, 'deleteRestaurant'])
                ->where('id', '[0-9]+'); // finished
        });

        Route::group(['prefix' => 'qr-restaurant'], function () {
            // Generate a QR code for restaurant chairs
            Route::get('/generate', [GenerateUrlAndQrController::class, 'ChairsBasedOnRestaurant']); // finished

            // // Update a QR code for restaurant chairs
            // Route::get('/{id}', [GenerateUrlAndQrController::class, 'getQrCode'])
            // ->where('id', '[0-9]+');

            // // Delete a QR code for restaurant chairs
            // Route::post('/delete', [GenerateUrlAndQrController::class,'deleteQrCode']);
        });

        Route::group(['prefix' => 'restaurant-users'], function () {
            // List users of a specific restaurant

            Route::get('{id}', [ManageUsersController::class, 'listOneRestaurantUsers'])
            ->where('id', '[0-9]+'); // finished

            // Create a new user for a restaurant
            Route::post('/add', [ManageUsersController::class, 'addRestaurantUsers']); // finished

            // Update a restaurant user's details
            Route::post('/update', [ManageUsersController::class, 'updateRestaurantUsers']); // finished

            // Delete a user from a restaurant
            Route::post('/delete', [ManageUsersController::class, 'deleteRestaurantUsers']); // finished
        });

        Route::group(['prefix' => 'package-restaurants'], function () {
            // List all subscribed package restaurants
            Route::get('/', [SubscriptionPackageRestaurantsController::class, 'listSubscriptionPackageRestaurant']); // finished

            // Add a new subscription for a package restaurant
            Route::post('/add', [SubscriptionPackageRestaurantsController::class, 'addSubscriptionPackageRestaurant']); // finished

            // Update an existing subscription for a package restaurant
            Route::post('/update', [SubscriptionPackageRestaurantsController::class, 'updateSubscriptionPackageRestaurant']); // finished

            // Delete a package restaurant's subscription
            Route::post('/delete', [SubscriptionPackageRestaurantsController::class, 'deleteSubscriptionPackageRestaurant']); // finished

            // archive a package restaurant's subscription
            Route::post('/archive', [SubscriptionPackageRestaurantsController::class, 'archiveSubscriptionPackageRestaurant']); // finished

            // list archive a package restaurant's subscription
            Route::get('/archive/list', [SubscriptionPackageRestaurantsController::class, 'listarchiveSubscriptionPackageRestaurant']); // finished

            // unarchive a package restaurant's subscription
            Route::post('/unarchive', [SubscriptionPackageRestaurantsController::class, 'unarchiveSubscriptionPackageRestaurant']); // finished
        });

        Route::group(['prefix' => 'packages-notifications'], function () {

            // List subscription notification packages
            Route::get('/', [SubscriptionNotificationsController::class, 'listSubscriptionNotificationsPackage']); // finished

            // Add a subscription notification package
            Route::post('add', [SubscriptionNotificationsController::class, 'addSubscriptionNotificationsPackage']); // finished

            // Update a subscription notification package
            Route::post('update', [SubscriptionNotificationsController::class, 'updateSubscriptionNotificationsPackage']); // finished

            // Delete a subscription notification package
            Route::post('delete', [SubscriptionNotificationsController::class, 'deleteSubscriptionNotificationsPackage']); // finished

            // archive a subscription notification package
            Route::post('archive', [SubscriptionNotificationsController::class, 'archiveSubscriptionNotificationsPackage']); // finished

            // list archive a subscription notification package
            Route::get('archive/list', [SubscriptionNotificationsController::class, 'listarchiveSubscriptionNotificationsPackage']); // finished

            // unarchive a subscription notification package
            Route::post('unarchive', [SubscriptionNotificationsController::class, 'unarchiveSubscriptionNotificationsPackage']); // finished
        });


        Route::group(['prefix' => 'restaurant-subscription'], function () {

            // View a restaurant's subscription
            Route::get('/', [RestaurantSubscrptionController::class, 'viewSubscrptionOfRestaurant']);

            // Add a new subscription to a restaurant
            Route::post('/add', [RestaurantSubscrptionController::class, 'addSubscrptionToRestaurant']);

            // Delete a subscription from a restaurant
            Route::delete('/delete', [RestaurantSubscrptionController::class, 'deleteSubscrptionToRestaurant']);

            // Update a restaurant's subscription
            Route::put('/update', [RestaurantSubscrptionController::class, 'updateSubscrptionToRestaurant']);

        });


        Route::group(['prefix' => 'notification-subscription'], function () {
            // View a restaurant's notification subscription
            Route::get('/', [RestaurantSubscrptionController::class, 'viewNotificationSubscrptionOfRestaurant']);

            // Add a notification subscription to a restaurant
            Route::post('/add', [RestaurantSubscrptionController::class, 'addNotificationSubscrptionToRestaurant']);

            // Delete a notification subscription from a restaurant
            Route::delete('/delete', [RestaurantSubscrptionController::class, 'deleteNotificationSubscrptionToRestaurant']);

            // Update a restaurant's notification subscription
            Route::put('/update', [RestaurantSubscrptionController::class, 'updateNotificationSubscrptionToRestaurant']);
        });


        Route::group(['prefix' => 'settings'], function () {
            Route::put('/about/update', [AboutController::class, 'updateAboutUs']);
            Route::put('/terms/update', [AboutController::class, 'updateTerms']);
            Route::put('/privacy-policy/update', [AboutController::class, 'updateTerms']);
        });

        // ========================================================================================================

        Route::group(['prefix' => 'questionable-chat'], function () {
            // Get questionable chats
            Route::get('/', [QuestionableChatController::class, 'getQuestionableChat']);
            // Accept a questionable chat
            Route::post('/accept', [QuestionableChatController::class, 'acceptQuestionableChat']);
            // Reject a questionable chat
            Route::post('/reject', [QuestionableChatController::class, 'rejectQuestionableChat']);
        });


        Route::group(['prefix' => 'restrictions'], function () {
            // List restricted words
            Route::get('/words', [RestrictionsController::class, 'listRestrictedWords']);
            // Add a restricted word
            Route::post('/words/add', [RestrictionsController::class, 'addRestrictedWords']);

            Route::post('/words/update', [RestrictionsController::class, 'updateRestrictedWords']);
            // Delete a restricted word
            // Assuming you pass the word or an identifier in the request
            Route::delete('/words/delete', [RestrictionsController::class, 'deleteRestrictedWords']);
        });


        Route::group(['prefix' => 'settings'], function () {
            // Update time settings for deleting messages
            Route::put('/update-message-delete-time', [DashboardSettingsController::class, 'updateTimeToDeleteMessages']);
            Route::put('/update-message-delete-time-followers', [DashboardSettingsController::class, 'updateTimeToDeleteMessagesForFollowers']);

            // Update logout time settings
            Route::put('/update-logout-times', [DashboardSettingsController::class, 'updateLogoutTimes']);
            Route::put('/update-logout-times-public', [DashboardSettingsController::class, 'updateLogoutTimesFromPublicPlaces']);

            // Stop application from a specific version
            Route::put('/stop-app-version', [DashboardSettingsController::class, 'stopApplicationFromVersion']);

            // Change user's profile details
            Route::put('/change-password', [DashboardSettingsController::class, 'changePassword']);
            Route::put('/change-email', [DashboardSettingsController::class, 'changeEmail']);
            Route::put('/change-name', [DashboardSettingsController::class, 'changeName']);
            Route::put('/change-photo', [DashboardSettingsController::class, 'changePhoto']);
        });

        Route::group(['prefix' => 'statistics'], function () {
            // Get count of restaurants with expired subscriptions
            Route::get('/expired-subscriptions', [StatisticsController::class, 'getCountRestaurantThatSubscriptionExpired']);

            // Get count of unanswered support messages
            Route::get('/unanswered-support-messages', [StatisticsController::class, 'getCountUnansweredSupportMessages']);

            // Get total count of support messages
            Route::get('/total-support-messages', [StatisticsController::class, 'getCountTotalSupportMessages']);

            // Get count of waiting restaurants
            Route::get('/waiting-restaurants', [StatisticsController::class, 'getCountWaitingRestaurants']);

            // Get total count of restaurants
            Route::get('/total-restaurants', [StatisticsController::class, 'getCountofAllRestaurants']);

            // Get statistics for public place entries
            Route::get('/public-places-entries', [StatisticsController::class, 'getPublicPlacesEntries']);

            // Get statistics for restaurant entries
            Route::get('/restaurant-entries', [StatisticsController::class, 'getRestaurantEntries']);

            // Get count of new customers from the last week
            Route::get('/new-customers-last-week', [StatisticsController::class, 'getCountNewCustomersLastWeek']);

            // Get total number of customers
            Route::get('/total-customers', [StatisticsController::class, 'getTotalCustomers']);
        });




    });
});

});
