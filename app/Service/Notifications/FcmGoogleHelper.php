<?php

namespace App\Service\Notifications;


use Google\Client as GClient;
use Google\Service\FirebaseCloudMessaging;
use Google_Exception;

class FcmGoogleHelper
{
    public static function configureClient()
    {
        $path = config('fcm.access_token_path');
        $client = new GClient();
        try {
            $client->setAuthConfig($path);
            $client->addScope(FirebaseCloudMessaging::CLOUD_PLATFORM);
            $accessToken = self::generateToken($client);
            $client->setAccessToken($accessToken);
            $oauthToken = $accessToken["access_token"];
            return $oauthToken;
        } catch (Google_Exception $e) {
            return null;
        }
    }

    private static function generateToken($client)
    {
        $client->fetchAccessTokenWithAssertion();
        $accessToken = $client->getAccessToken();
        return $accessToken;
    }
}
