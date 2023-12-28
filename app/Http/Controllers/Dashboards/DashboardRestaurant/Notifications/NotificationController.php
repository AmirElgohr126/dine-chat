<?php
namespace App\Http\Controllers\Dashboards\DashboardRestaurant\Notifications;

use Exception;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\UserAttendance;
use App\Http\Controllers\Controller;
use App\Http\Requests\Notifications\CreateNotificationRequest;
use App\Http\Resources\Dashboard\Notifications\NotificationResource;

class NotificationController extends Controller
{
    public function createNotification(CreateNotificationRequest $request)
    {
        try {
            $request->validated();
            $user = $request->user('restaurant');
            $path = 'Dafaults/Notification/logo_notification.png';
            $photo = $request->photo;
            if($photo)
            {
                $path = storeFile($photo, "restaurant_$user->id/Notification", 'public');
            }
            $notificationData = [
                'restaurant_id' => $user->restaurant_id,
                'title' => $request->title,
                'message' => $request->message,
                'status' => $request->status,
                'sent_at' => $request->status == 'pending' ? $request->sent_at : null ,
                'photo' => $path
            ];
            $notification = Notification::create($notificationData);
            if (!$notification) {
                throw new Exception(__('errors.failed_create_notification'), 500);
            }
            return finalResponse('success', 200, __('errors.succees_notification_create'), null);
        } catch (Exception $e) {
            return finalResponse('failed', 500, null, null, $e->getMessage());
        }
    }


    public function listNotification(Request $request)
    {
        try {
            $per_page = $request->per_page ?? 10;
            $user = $request->user('restaurant');

            // Fetch notifications for the given restaurant with related data
            $notifications = Notification::where('restaurant_id', $user->restaurant_id)
                ->orderBy('created_at', 'desc') // You can adjust the ordering based on your needs
                ->paginate($per_page);

            $notifications = NotificationResource::collection($notifications);
            $pagination = pagnationResponse($notifications);
            return finalResponse('success', 200, $notifications->items(), $pagination);
        } catch (Exception $e) {
            return finalResponse('failed', 400, null, null, $e->getMessage());
        }
    }


    public function sendNotificationNow(Request $request)
    {
        $user = $request->user('restaurant');
        $accessToken = app('firebase.access_token'); // Get the access token

        $restaurantId = $user->restaurant_id; // replace with your desired restaurant_id
        $uniqueUserIds = UserAttendance::where('restaurant_id', $restaurantId)
            ->distinct()
            ->pluck('user_id');
        $deviceTokens = User::whereIn('id', $uniqueUserIds)
            ->pluck('device_token'); // 'device_tokens' in table users
        if (empty($deviceTokens[0])) {
            return finalResponse('failed', 400, null, null, 'no participants');
        }
        $projectId = 'dinechate';
        $Notification = Notification::where('restaurant_id', $restaurantId)->where('id', $request->id)->first();

        $responses = [];

        foreach ($deviceTokens as $deviceToken) {
            $curl = curl_init();
            $postData = [
                'message' => [
                    'notification' => [
                        'title' => $Notification->title,
                        'body' => $Notification->message,
                        'image' => retriveMedia() . $Notification->photo
                    ],
                    'token' => $deviceToken, // Use single device token
                ]
            ];

            $headers = [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ];

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://fcm.googleapis.com/v1/projects/$projectId/messages:send",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($postData),
                CURLOPT_HTTPHEADER => $headers,
            ]);

            $response = curl_exec($curl);

            if (!curl_errno($curl)) {
                $responses[] = json_decode($response, true);
            }

            curl_close($curl);
        }

        return $responses;
    }

}

?>
