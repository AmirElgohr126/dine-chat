<?php
namespace App\Http\Controllers\V1\Dashboards\DashboardRestaurant\Notifications;

use Exception;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Psr7\Request;
use App\Http\Controllers\V1\Dashboards\DashboardRestaurant\Notifications\FcmGoogleHelper;

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
            'fulfilled' => function ($response, $index) use (&$successfulTokens, &$failedTokens, $deviceTokens) {
                $statusCode = $response->getStatusCode();
                $responseData = json_decode($response->getBody()->getContents(), true);

                // Check HTTP status code for success
                if ($statusCode == 200) {
                    // FCM v1 successful response contains 'name' or 'message_id'
                    if (isset ($responseData['name']) || (isset ($responseData['results']) && isset ($responseData['results'][0]['message_id']))) {
                        $successfulTokens[] = $deviceTokens[$index];
                    } else {
                        // If 'name' or 'message_id' is not present, consider it a failure.
                        // There may be an error message in the response.
                        $errorMessage = isset ($responseData['error']) ? $responseData['error'] : 'Error without detail';
                        $failedTokens[$deviceTokens[$index]] = $errorMessage;
                    }
                } else {
                    // Non-200 responses are treated as failures.
                    $errorMessage = isset ($responseData['error']) ? $responseData['error'] : 'Error without detail';
                    $failedTokens[$deviceTokens[$index]] = $errorMessage;
                }
            },
            'rejected' => function ($reason, $index) use (&$failedTokens, $deviceTokens) {
                // Handle network-level errors or other Guzzle exceptions
                $errorMessage = $reason instanceof Exception ? $reason->getMessage() : 'Request rejected without exception';
                $failedTokens[$deviceTokens[$index]] = $errorMessage;
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
