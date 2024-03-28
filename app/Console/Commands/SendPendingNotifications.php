<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Notification;
use App\Models\UserAttendance;
use Illuminate\Console\Command;
use App\Http\Controllers\V1\Dashboards\DashboardRestaurant\Notifications\NotificationSender;

class SendPendingNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-pending-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send all pending notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pendingNotifications = Notification::where('status', 'pending')->where('sent_at', '<', now())->get();
        if ($pendingNotifications->isEmpty()) {
            $this->info('No pending notifications to send.');
            return;
        }
        $projectId = 'dine-chat';
        $notificationController = new NotificationSender($projectId);

        foreach ($pendingNotifications as $notification) {
            try {
                $uniqueUserIds = UserAttendance::where('restaurant_id', $notification->restaurant_id)->distinct()->pluck('user_id');
                $deviceTokens = User::whereIn('id', $uniqueUserIds)->pluck('device_token');
                $deviceTokens = array_filter($deviceTokens->toArray());
                $result = $notificationController->sendNotification($notification, $deviceTokens);

                if ($result['successful'] > 0) {
                    $notification->last_sent_at = now();
                    $notification->status = 'sent';
                    $notification->sent_at = now();
                    $notification->save();
                    $this->info("Successfully sent notification {$notification->id}");
                } else {
                    $this->error("Failed to send notification {$notification->id} - no successful sends");
                }
            } catch (\Exception $e) {
                $this->error("Failed to send notification {$notification->id}: {$e->getMessage()}");
            }
        }
    }

}
