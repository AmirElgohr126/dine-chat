
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboards\DashboardAdmin\Auth\ApplcationController;
use App\Http\Controllers\Dashboards\DashboardAdmin\Auth\LoginAdminController;
use App\Http\Controllers\Dashboards\DashboardAdmin\Statistics\StatisticsController;
use App\Http\Controllers\Dashboards\DashboardAdmin\Resturants\Users\ManageUsersController;
use App\Http\Controllers\Dashboards\DashboardAdmin\Settings\AboutSettings\AboutController;
use App\Http\Controllers\Dashboards\DashboardAdmin\Settings\ChatSettings\RestrictionsController;
use App\Http\Controllers\Dashboards\DashboardAdmin\PublicPlaces\PublicPlaces\PublicPlacesContoller;
use App\Http\Controllers\Dashboards\DashboardAdmin\PublicPlaces\QrCodeForPlaces\QrPlacesController;
use App\Http\Controllers\Dashboards\DashboardAdmin\Settings\ChatSettings\QuestionableChatController;
use App\Http\Controllers\Dashboards\DashboardAdmin\Resturants\GenerateUrlChairs\GenerateUrlController;
use App\Http\Controllers\Dashboards\DashboardAdmin\Resturants\Restaurants\ManageRestaurantsController;
use App\Http\Controllers\Dashboards\DashboardAdmin\Settings\PublicSettings\DashboardSettingsController;
use App\Http\Controllers\Dashboards\DashboardAdmin\Resturants\Notifications\manageNotificationsController;
use App\Http\Controllers\Dashboards\DashboardAdmin\Resturants\Restaurants\RestaurantSubscrptionController;
use App\Http\Controllers\Dashboards\DashboardAdmin\SubscriptionPackages\SubscriptionPackagesNotifications\SubscriptionNotificationsController;
use App\Http\Controllers\Dashboards\DashboardAdmin\SubscriptionPackages\SubscriptionPackagesRestaurants\SubscriptionPackageRestaurantsController;


// Define a route for the ChairsBasedOnRestaurant method
// Route::get('/chairs/urls', [GenerateUrlController::class, 'ChairsBasedOnRestaurant']);

Route::middleware('set_lang')->group(function () {


    Route::group(['prefix' => 'application'], function () {

        Route::get('/', [ApplcationController::class, 'listApplacation']); // list applications
        Route::post('{application}/accept', [ApplcationController::class, 'acceptApplacation'])
            ->where('application', '[0-9]+'); // accept an application
        Route::post('{application}/reject', [ApplcationController::class, 'rejectApplacation'])
            ->where('application', '[0-9]+'); // reject an application
    });
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', [LoginAdminController::class, 'loginDashboard']);
        Route::post('/reset-password', [LoginAdminController::class, 'resetPasswordDashboard'])->middleware('auth:admin');
    });

    Route::group(['prefix' => 'public-places'], function () {
        Route::post('/', [PublicPlacesContoller::class, 'addPublicPlace']); // Create a new public place
        Route::get('/', [PublicPlacesContoller::class, 'listPublicPlace']); // List all public places
        Route::put('/{place}', [PublicPlacesContoller::class, 'updatePublicPlace'])
            ->where('place', '[0-9]+'); // Update a specific public place
        Route::delete('/{place}', [PublicPlacesContoller::class, 'deletePublicPlace'])
            ->where('place', '[0-9]+'); // Delete a specific public place
    });

    Route::group(['prefix' => 'qr-places'], function () {
        Route::post('/generate', [QrPlacesController::class, 'generateQrCode']); // Generate a QR code for public places
        Route::put('/update', [QrPlacesController::class, 'updateQrCode']); // Update a QR code for public places
        Route::delete('/delete', [QrPlacesController::class, 'deleteQrCode']); // Delete a QR code for public places
    });

    Route::group(['prefix' => 'restaurant-notifications'], function () {
        // List notifications for a specific restaurant
        Route::get('/list', [manageNotificationsController::class, 'listNotificationForOneRestaurant']);

        // Create a notification for a specific restaurant
        Route::post('/create', [manageNotificationsController::class, 'createNotificationForOneRestaurant']);
    });

    Route::group(['prefix' => 'restaurants'], function () {
        Route::get('/', [ManageRestaurantsController::class, 'listRestaurant']); // List restaurants
        Route::post('/add', [ManageRestaurantsController::class, 'addRestaurant']); // Create a new restaurant

        Route::put('/update/{restaurant}', [ManageRestaurantsController::class, 'updateRestaurant'])
            ->where('restaurant', '[0-9]+'); // Update a restaurant
        Route::delete('/delete/{restaurant}', [ManageRestaurantsController::class, 'deleteRestaurant'])
            ->where('restaurant', '[0-9]+'); // Delete a restaurant

    });

    Route::group(['prefix' => 'restaurant-subscription'], function () {
        // Add a new subscription to a restaurant
        Route::post('/add', [RestaurantSubscrptionController::class, 'addSubscrptionToRestaurant']);

        // Delete a subscription from a restaurant
        Route::delete('/delete', [RestaurantSubscrptionController::class, 'deleteSubscrptionToRestaurant']);

        // Update a restaurant's subscription
        Route::put('/update', [RestaurantSubscrptionController::class, 'updateSubscrptionToRestaurant']);

        // View a restaurant's subscription
        Route::get('/view', [RestaurantSubscrptionController::class, 'viewSubscrptionOfRestaurant']);
    });

    Route::group(['prefix' => 'restaurant-notification-subscription'], function () {
        // Add a notification subscription to a restaurant
        Route::post('/add', [RestaurantSubscrptionController::class, 'addNotificationSubscrptionToRestaurant']);
        // Delete a notification subscription from a restaurant
        Route::delete('/delete', [RestaurantSubscrptionController::class, 'deleteNotificationSubscrptionToRestaurant']);
        // Update a restaurant's notification subscription
        Route::put('/update', [RestaurantSubscrptionController::class, 'updateNotificationSubscrptionToRestaurant']);
        // View a restaurant's notification subscription
        Route::get('/view', [RestaurantSubscrptionController::class, 'viewNotificationSubscrptionOfRestaurant']);
    });



    Route::group(['prefix' => 'restaurant-users'], function () {
        // List users of a specific restaurant
        // Assuming you pass a restaurant ID in the URL
        Route::get('/list/{restaurant}', [ManageUsersController::class, 'listOneRestaurantUsers'])
            ->where('restaurant', '[0-9]+');

        // Create a new user for a restaurant
        Route::post('/add', [ManageUsersController::class, 'addRestaurantUsers']);

        // Update a restaurant user's details
        // Assuming you pass a user ID in the URL
        Route::put('/update/{user}', [ManageUsersController::class, 'updateRestaurantUsers'])
            ->where('user', '[0-9]+');

        // Delete a user from a restaurant
        // Assuming you pass a user ID in the URL
        Route::delete('/delete/{user}', [ManageUsersController::class, 'deleteRestaurantUsers'])
            ->where('user', '[0-9]+');
    });


    Route::group(['prefix' => 'about-settings'], function () {
        Route::post('/about/create', [AboutController::class, 'createAboutUs']);
        Route::put('/about/update', [AboutController::class, 'updateAboutUs']);
        Route::post('/terms/create', [AboutController::class, 'createTerms']);
        Route::put('/terms/update', [AboutController::class, 'updateTerms']);
    });


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


    Route::group(['prefix' => 'subscription-notifications'], function () {
        // List subscription notification packages
        Route::get('/packages', [SubscriptionNotificationsController::class, 'listSubscriptionNotificationsPackage']);

        // Add a subscription notification package
        Route::post('/packages/add', [SubscriptionNotificationsController::class, 'addSubscriptionNotificationsPackage']);

        // Update a subscription notification package
        Route::put('/packages/update', [SubscriptionNotificationsController::class, 'updateSubscriptionNotificationsPackage']);

        // Delete a subscription notification package
        Route::delete('/packages/delete', [SubscriptionNotificationsController::class, 'deleteSubscriptionNotificationsPackage']);
    });

    Route::group(['prefix' => 'subscription-package-restaurants'], function () {
        // List all subscribed package restaurants
        Route::get('/', [SubscriptionPackageRestaurantsController::class, 'listSubscriptionPackageRestaurant']);

        // Add a new subscription for a package restaurant
        Route::post('/add', [SubscriptionPackageRestaurantsController::class, 'addSubscriptionPackageRestaurant']);

        // Update an existing subscription for a package restaurant
        Route::put('/update', [SubscriptionPackageRestaurantsController::class, 'updateSubscriptionPackageRestaurant']);

        // Delete a package restaurant's subscription
        Route::delete('/delete', [SubscriptionPackageRestaurantsController::class, 'deleteSubscriptionPackageRestaurant']);
    });



});
