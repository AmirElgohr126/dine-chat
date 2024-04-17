<?php
namespace App\Http\Controllers\V1\App\Notifications;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\App\Notifications\NotificationsResources;

class NotificationController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getNotifications(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        $perPage = $request->per_page ?? 10;

        // Adjust the join to include restaurant_translations for the name column
        $notifications = Notification::join('notification_user', 'notifications.id', '=', 'notification_user.notification_id')
            ->join('restaurants', 'notifications.restaurant_id', '=', 'restaurants.id')
            ->join('restaurant_translations', function ($join) {
                $join->on('restaurants.id', '=', 'restaurant_translations.restaurant_id')
                    ->where('restaurant_translations.locale', '=', app()->getLocale()); // Adjust based on your app's current locale
            })
            ->where('notification_user.user_id', $user->id)
            ->orderBy('notifications.last_sent_at', 'desc')
            ->select([
                'notification_user.id',
                'notifications.photo',
                'notifications.message',
                'notifications.sent_at',
                'restaurant_translations.name as restaurant_name' // Alias to avoid ambiguity
            ])
            ->paginate($perPage);

        $pagination = pagnationResponse($notifications);
        $notificationsResponse = NotificationsResources::collection($notifications);
        return finalResponse('success', 200, $notificationsResponse, $pagination);
    }

}

