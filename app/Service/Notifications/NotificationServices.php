<?php
namespace App\Service\Notifications;

use App\Service\Notifications\NotificationInterface;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Psr7\Request;

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
     * return of Access Token
     * @return mixed
     */
    public function getAccessToken()
    {
        return FcmGoogleHelper::configureClient();
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
        $curl = curl_init();
        $postData = [
            'message' => [
                'notification' => [
                    'title' => $this->notification['title'],
                    'body' => $this->notification['message'],
                    'image' => $this->notification['photo'] ? retriveMedia() . $this->notification['photo'] : ''
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
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://fcm.googleapis.com/v1/projects/$this->projectId/messages:send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => $this->headers,
        ]);
        $response = curl_exec($curl);
        if (!curl_errno($curl)) {
            $responses[] = json_decode($response, true);
        }
        curl_close($curl);
        return $responses;
    }
    /**
     *
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
                            'image' => $this->notification['photo'] ? retriveMedia() . $this->notification['photo'] : '' ,
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

?>
