<?php
namespace App\Http\Controllers\App\Notifications;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Notifications\AppNotifications;

class NotificationController extends Controller
{
    // list all notification on app

    public function getNotifications(Request $request)
    {
        $user = $request->user();
        $perPage = $request->per_page ?? 10;
        // Fetch notifications linked to the user through the notification_user table
        $notifications = Notification::join('notification_user', 'notifications.id', '=', 'notification_user.notification_id')
            ->where('notification_user.user_id', $user->id)
            ->orderBy('notifications.last_sent_at', 'desc') // Sort by last_sent_at in descending order
            ->select(['notification_user.id', 'notifications.photo', 'notifications.message', 'notifications.sent_at']) // Select specific fields
            ->paginate($perPage);
        $pagnations = pagnationResponse($notifications);
        $notificationsResponse = AppNotifications::collection($notifications);
        return finalResponse('success',200,$notificationsResponse,$pagnations);
    }
}

?>
