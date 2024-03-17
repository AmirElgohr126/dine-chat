<?php
namespace App\Http\Controllers\V1\Dashboards\DashboardRestaurant\Notifications;

use Exception;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\NotificationUser;
use App\Models\HistoryAttendances;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Dashboard\Notifications\CreateNotificationRequest;
use App\Http\Resources\V1\DashboardRestaurant\Notifications\NotificationResource;
use App\Http\Controllers\V1\Dashboards\DashboardRestaurant\Notifications\NotificationSender;

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
                $restaurantId = $user->restaurant_id;
                $path = storeFile($photo, "restaurants/restaurant{$restaurantId}/notifications", 'public');
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

            if($notification->status == 'send_now')
            {
                $result = $this->sendNotify($notification, $user->restaurant_id);
                if (!$result) {
                    return finalResponse('failed', 400, null, null, 'no participants');
                }
                if ($result['successful'] > 0) {
                    $notification->last_sent_at = now();
                    $notification->status = 'sent';
                    $notification->sent_at = now();
                    $notification->save();
                }
            }


            if (!$notification) {
                throw new Exception(__('errors.failed_create_notification'), 500);
            }
            return finalResponse('success', 200, __('errors.succees_notification_create'), null);
        } catch (Exception $e) {
            return finalResponse('failed', 500, null, null, $e->getMessage());
        }
    }

    public function deleteNotification(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:notifications,id'
            ]);
            $user = $request->user('restaurant');
            $notification = Notification::where('restaurant_id', $user->restaurant_id)
            ->where('id',$request->id)->first();
            if($notification)
            {
                $notification->delete();
            }else{
                throw new Exception("notification not belongs to this restaurant", 400);
            }
            return finalResponse('success', 200,'deleted sucessfully');
        } catch (Exception $e) {
            return finalResponse('failed', 400, null, null, $e->getMessage());
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
        $restaurantId = $user->restaurant_id;
        $Notification = Notification::where('restaurant_id', $restaurantId)->where('id', $request->id)->first();
        if(!$Notification)
        {
            return finalResponse('failed', 400, null, null, 'no notification with id '.$request->id);
        }
        try {
            $result = $this->sendNotify($Notification, $user->restaurant_id); // return false if no participants

            if (!$result) {
                return finalResponse('failed', 400, null, null, 'no participants');
            }
            if ($result['successful'] > 0) {
                // At least one notification was sent successfully
                $Notification->last_sent_at = now();
                $Notification->status = 'sent';
                $Notification->sent_at = now();
                $Notification->save();
                return finalResponse('success', 200, null, null, 'notifications sent');
            } else {
                return finalResponse('failed', 400, null, null, 'Notification sending but no participants found.');
            }
        } catch (Exception $e) {
            return finalResponse('failed', 400, null, null, $e->getMessage());
        }
    }

    private function sendNotify($Notification,$restaurantId)
    {
        $uniqueUserIds = HistoryAttendances::where('restaurant_id', $restaurantId)->pluck('user_id'); // users id .
        $deviceTokens = User::whereIn('id', $uniqueUserIds)->pluck('device_token'); // tokens of restaurant
        $deviceTokens = array_filter($deviceTokens->toArray()); // empty invailed tokens
        if (empty($deviceTokens)) {
            return false;
        }
        $projectId = 'dine-chat';
        $notificationSender = new NotificationSender($projectId);
        $result =  $notificationSender->sendNotification($Notification, $deviceTokens);

        if (!empty($result['successfulTokens'])) {
            $successfulUserIds = User::whereIn('device_token', $result['successfulTokens'])->pluck('id');
            foreach ($successfulUserIds as $userId) {
                NotificationUser::create([
                    'user_id' => $userId,
                    'notification_id' => $Notification->id,
                ]);
            }
        }
        return $result;
    }
}
