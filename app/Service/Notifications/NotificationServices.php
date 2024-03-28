<?php
namespace App\Service\Notifications;

use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\GuzzleException;
use App\Service\Notifications\NotificationInterface;

/**
 * Summary of NotificationServices
 */
class NotificationServices implements NotificationInterface
{


    private $client;
    /**
     *  headers of request
     * @var
     */
    private $headers;
    /**
     * projectId
     * @var
     */
    private $projectId;
    /**
     * notification will send
     *
     * @var
     */
    public $notification;

    /**
     * set Header and configurtions
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->projectId = config('fcm.project-id');
        $this->headers = [
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * return of Access Token
     * @return mixed
     */
    public function getAccessToken()
    {
        return FcmGoogleHelper::configureClient();
    }


    /**
     * setNotification to array
     * @param mixed $notification
     * @return void
     */
    protected function setNotification($notification)
    {
        if (!is_array($notification)) {
            $notification = $notification->toArray();
            $this->notification = $notification;
        }
        $this->notification = $notification;
    }



    /**
     * send one notify to one Device
     * @param mixed $notification
     * @param mixed $deviceToken
     * @return array
     */
    public function sendOneNotifyOneDevice($notification, $deviceToken)
    {
        $this->setNotification($notification);
        $postData = [
            'message' => [
                'notification' => [
                    'title' => $this->notification['title'],
                    'body' => $this->notification['message'],
                    'image' => $this->notification['image'] ? retriveMedia() . $this->notification['image'] : ''
                ],
                'android' => [
                    'notification' => [
                        'sound' => 'default'
                    ]
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'sound' => 'default'
                        ]
                    ]
                ],
                'token' => $deviceToken,
            ]
        ];
        try {
            $response = $this->client->post("https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send", [
                'headers' => $this->headers,
                'json' => $postData,
            ]);

            // Decode the JSON response into an associative array.
            $responseData = json_decode($response->getBody()->getContents(), true);

            // Assuming you want to return an array of responses like the original curl version.
            $responses = [$responseData];
            return $responses;
        } catch (GuzzleException $e) {
            // Handle the exception according to your needs.
            // For example, you could log the error or return a specific error structure.
            return ['error' => $e->getMessage()];
        }
    }



    /**
     * send one notify to multi Device in one request
     * @param mixed $notification
     * @param mixed $deviceTokens
     */
    public function sendOneNotifyMultiDevice($notification, $deviceTokens)
    {
        $this->setNotification($notification);
        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
        $requests = function ($deviceTokens) use ($url) {
            foreach ($deviceTokens as $token) {
                $postData = [
                    'message' => [
                        'notification' => [
                            'title' => $this->notification['title'],
                            'body' => $this->notification['message'],
                            'image' => $this->notification['image'] ? retriveMedia() . $this->notification['image'] : '' ,
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
        $successful = 0;
        $failed = 0;
        $pool = new Pool($this->client, $requests($deviceTokens), [
            'concurrency' => 500,
            'fulfilled' => function ($response, $index) use (&$successful) {
                $successful++;
            },
            'rejected' => function ($reason, $index) use (&$failed) {
                $failed++;
            },
        ]);
        $promise = $pool->promise();
        $promise->wait();
        return ['successful' => $successful, 'failed' => $failed];
    }

}

