<?php
namespace App\Service\Notifications;


interface NotificationInterface
{
    public function getAccessToken();

    public function sendOneNotifyOneDevice(array $Notification,string $deviceToken);

    public function sendOneNotifyMultiDevice(array $Notification,array $deviceTokens);

}

?>
