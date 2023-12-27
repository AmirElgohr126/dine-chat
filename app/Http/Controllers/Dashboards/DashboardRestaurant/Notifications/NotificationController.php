<?php
namespace App\Http\Controllers\Dashboards\DashboardRestaurant\Notifications;

use Exception;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function createNotification(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'message' => 'required|string',
            ]);
            // Create a new notification
            $notification = Notification::create([
                'restaurant_id' => $request->user('restaurant')->restaurant_id,
                'title' => $request->title,
                'message' => $request->message,
                'status' => 'invalid', // pending - send now
            ]);
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

            $pagination = pagnationResponse($notifications);

            return finalResponse('success', 200, $notifications->items(), $pagination);
        } catch (Exception $e) {
            return finalResponse('failed', 400, null, null, $e->getMessage());
        }
    }


    public function sendNotification(Request $request)
    {
        
    }
}


?>
