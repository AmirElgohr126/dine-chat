<?php
namespace App\Service\XoServices;

interface XoGameInterface
{
    public function RequestToPlay($senderId, $receiverId, $restaurantId);

    public function cancelRequest($roomId, $senderId);

    public function AcceptInvite($roomId, $receiverId);

    public function cancelInvite($roomId, $receiverId);

    public function listInvites($receiverId);
    public function listRequests($senderId);

}


