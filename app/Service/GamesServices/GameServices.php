<?php
namespace App\Service\GamesServices;

use App\Models\Room;
use App\Service\GamesServices\GameInterface;

class GameServices implements GameInterface
{

    /**
     * @param $senderId
     * @param $receiverId
     * @param $placeId
     * @param $place
     * @param $type_room
     * @return mixed
     */
    public function RequestToPlay($senderId, $receiverId, $placeId,$place, $type_room): mixed
    {
        $room = Room::create([
            'receiver_id' => $receiverId,
            'sender_id' => $senderId,
            $place => $placeId,
            'type_room' => $type_room,
            'status' => 'invited',
        ]);
        return $room;
    }



    /**
     * cancel the request to play
     * @param $roomId
     * @param $senderId
     * @return mixed
     */
    public function cancelRequest($roomId, $senderId): mixed
    {
        // Find the game invitation and cancel it
        $room = Room::where('id',$roomId)->where('sender_id', $senderId);
        $room->delete();

        return $room;
    }



    /**
     * Accept the invitation to play
     * @param $roomId
     * @param $receiverId
     * @return mixed
     */
    public function AcceptInvite($roomId,$receiverId): mixed
    {
        // Find the invitation and update its status to 'accepted'
        $room = Room::where('id', $roomId)->where('receiver_id', $receiverId)->first();
        $room->status = 'accept';
        $room->save();
        return $room;
    }



    /**
     * cancel the invitation to play
     * @param $roomId
     * @param $receiverId
     * @return mixed
     */
    public function cancelInvite($roomId, $receiverId): mixed
    {
        // Find the invitation and update its status to 'rejected'
        $room = Room::where('id', $roomId)->where('receiver_id', $receiverId)->first();
        $room->status = 'reject';
        $room->save();
        return $room;
    }



    /**
     * list the invitations to play
     * @param mixed $receiverId
     */
    function listInvites($receiverId)
    {
        return Room::where('receiver_id', $receiverId)
            ->where('status', 'invited')
            ->get();
    }


    /**
     * list the requests to play
     * @param mixed $senderId
     */
    function listRequests($senderId)
    {
        return Room::where('sender_id', $senderId)
            ->where('status', 'invited')
            ->get();
    }
}

