<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Notification;
use App\Models\UserAttendance;
use Illuminate\Console\Command;
use App\Http\Controllers\Dashboards\DashboardRestaurant\Notifications\NotificationSender;

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
        $pendingNotifications = Notification::where('status', 'pending')->get();
        if ($pendingNotifications->isEmpty()) {
            $this->info('No pending notifications to send.');
            return;
        }
        $projectId = 'dine-chat';
        $notificationController = new NotificationSender($projectId);
        foreach ($pendingNotifications as $notification) {
            try {
                $uniqueUserIds = UserAttendance::where('restaurant_id', $notification->restaurant_id)->distinct()->pluck('user_id');
                $deviceTokens = User::whereIn('id', $uniqueUserIds)->pluck('device_token'); // tokens of restaurant
                $deviceTokens = array_filter($deviceTokens->toArray());
                $notificationController->sendNotification($notification,$deviceTokens);
                $notification->status = 'sent'; // Update the status
                $notification->save();
                $this->info("Sent notification {$notification->id}");
            } catch (\Exception $e) {
                $this->error("Failed to send notification {$notification->id}: {$e->getMessage()}");
            }
        }
    }
}
