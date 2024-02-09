<?php
namespace App\Service\GamesServices;

use App\Models\Room;
use App\Service\GamesServices\GameInterface;

class GameServices implements GameInterface
{

    public function RequestToPlay($senderId, $receiverId, $restaurantId, $type_room) {
        $room = Room::create([
            'receiver_id' => $receiverId,
            'sender_id' => $senderId,
            'restaurant_id' => $restaurantId,
            'type_room' => $type_room,
            'status' => 'invited',
        ]);
        return $room;
    }

    /**
     */
    public function cancelRequest($roomId,$senderId) {
        // Find the game invitation and cancel it
        $room = Room::where('id',$roomId)->where('sender_id', $senderId);
        $room->delete();

        return $room;
    }

    /**
     */
    public function AcceptInvite($roomId,$receiverId)
    {
        // Find the invitation and update its status to 'accepted'
        $room = Room::where('id', $roomId)->where('receiver_id', $receiverId)->first();
        $room->status = 'accept';
        $room->save();
        return $room;
    }

    /**
     */
    public function cancelInvite($roomId, $receiverId)
    {
        // Find the invitation and update its status to 'rejected'
        $room = Room::where('id', $roomId)->where('receiver_id', $receiverId)->first();
        $room->status = 'reject';
        $room->save();
        return $room;
    }
    /**
     *
     * @param mixed $receiverId
     */
    function listInvites($receiverId)
    {
        return Room::where('receiver_id', $receiverId)
            ->where('status', 'invited')
            ->get();
    }

    /**
     * @param mixed $senderId
     */
    function listRequests($senderId)
    {
        return Room::where('sender_id', $senderId)
            ->where('status', 'invited')
            ->get();
    }
}

