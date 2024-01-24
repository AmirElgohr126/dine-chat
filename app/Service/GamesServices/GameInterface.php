<?php
namespace App\Service\GamesServices;

interface GameInterface
{
    public function RequestToPlay($type_room,$senderId, $receiverId, $restaurantId);

    public function cancelRequest($roomId, $senderId);

    public function AcceptInvite($roomId, $receiverId);

    public function cancelInvite($roomId, $receiverId);

    public function listInvites($receiverId);

    public function listRequests($senderId);

}


