<?php

namespace App\Providers;

use Google\Auth\OAuth2;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use App\Service\Notifications\NotificationServices;
use App\Service\Notifications\NotificationInterface;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */


    public function register()
    {
        $this->app->bind(NotificationInterface::class, NotificationServices::class);
    }



    // public function register()
    // {
    //     // $this->app->singleton('firebase.access_token', function ($app) {
    //     //     return $this->getAccessToken();
    //     // });
    // }

    // protected function getAccessToken()
    // {
    //     // $cachedToken = Cache::get('firebase_access_token');

    //     // if ($cachedToken) {
    //     //     return $cachedToken;
    //     // }

    //     // $json_key = json_decode(file_get_contents(base_path('dinechate-firebase-adminsdk-qcocz-80a2616b4b.json')), true);
    //     // $oauth2 = new OAuth2([
    //     //     'audience' => 'https://accounts.google.com/o/oauth2/token',
    //     //     'issuer' => $json_key['client_email'],
    //     //     'signingAlgorithm' => 'RS256',
    //     //     'signingKey' => $json_key['private_key'],
    //     //     'tokenCredentialUri' => 'https://oauth2.googleapis.com/token',
    //     //     'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
    //     // ]);
    //     // $oauth2->fetchAuthToken();
    //     // $accessToken = $oauth2->getLastReceivedToken()['access_token'];
    //     // Cache::put('firebase_access_token', $accessToken, 50 * 60);
    //     // return $accessToken;
    // }
    
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
