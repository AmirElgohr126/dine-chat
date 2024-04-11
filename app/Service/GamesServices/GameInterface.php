<?php
namespace App\Service\GamesServices;

interface GameInterface
{
    public function RequestToPlay($senderId, $receiverId, $placeId,$place, $type_room);

    public function cancelRequest($roomId, $senderId);

    public function AcceptInvite($roomId, $receiverId);

    public function cancelInvite($roomId, $receiverId);

    public function listInvites($receiverId);

    public function listRequests($senderId);

}


