<?php
namespace App\Http\Controllers\Dashboards\DashboardRestaurant\Notifications;

use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Psr7\Request;
use App\Http\Controllers\Dashboards\DashboardRestaurant\Notifications\FcmGoogleHelper;

class NotificationSender
{
    private $client;
    private $headers;
    private $projectId;

    public function __construct($projectId)
    {
        $this->client = new Client();
        $this->projectId = $projectId;
        $this->headers = [
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json',
        ];
    }

    private function getAccessToken()
    {
        return FcmGoogleHelper::configureClient();
    }


    public function sendNotification($notification, $deviceTokens)
    {
        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $requests = function ($deviceTokens) use ($notification, $url) {
            foreach ($deviceTokens as $token) {
                $postData = [
                    'message' => [
                        'notification' => [
                            'title' => $notification->title,
                            'body' => $notification->message,
                            'image' => retriveMedia() . $notification->photo,
                        ],
                        'android' => [
                            'notification' => [
                                'sound' => 'default',
                            ],
                        ],
                        'apns' => [
                            'payload' => [
                                'aps' => [
                                    'sound' => 'default',
                                ],
                            ],
                        ],
                        'token' => $token,
                    ],
                ];
                yield new Request('POST', $url, $this->headers, json_encode($postData));
            }
        };
        $successfulTokens = [];
        $failedTokens = [];
        $pool = new Pool($this->client, $requests($deviceTokens), [
            'concurrency' => 500,
            'fulfilled' => function ($response, $index) use (&$successfulTokens, $deviceTokens) {
                $successfulTokens[] = $deviceTokens[$index];
            },
            'rejected' => function ($reason, $index) use (&$failedTokens, $deviceTokens) {
                $failedTokens[] = $deviceTokens[$index];
            },
        ]);
        $promise = $pool->promise();
        $promise->wait();
        return [
            'successful' => count($successfulTokens),
            'failed' => count($failedTokens),
            'successfulTokens' => $successfulTokens,
            'failedTokens' => $failedTokens
        ];
    }


}
