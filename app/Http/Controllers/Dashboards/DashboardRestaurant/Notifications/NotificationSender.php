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


    public function sendNotify($notification, $deviceTokens)
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

        $pool = new Pool($this->client, $requests($deviceTokens), [
            'concurrency' => 500,
            'fulfilled' => function ($response, $index) {
                // Handle successful response
                // $response->getBody() for response body if needed
                echo "Notification sent to device {$index}\n";
            },
            'rejected' => function ($reason, $index) {
                // Handle error
                echo "Failed to send notification to device {$index}: {$reason}\n";
            },
        ]);

        $promise = $pool->promise();
        $promise->wait();
        return true;
    }
    private function getAccessToken()
    {
        return FcmGoogleHelper::configureClient();
    }

}

?>
