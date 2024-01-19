<?php
namespace App\Service\XoServices;

use App\Models\XORoom;

class XoGameServices implements XoGameInterface
{

    public function RequestToPlay($senderId, $receiverId, $restaurantId) {
        $xoRoom = XORoom::create([
            'receiver_id' => $receiverId,
            'sender_id' => $senderId,
            'restaurant_id' => $restaurantId,
            'status' => 'invited',
        ]);
        return $xoRoom;
    }

    /**
     */
    public function cancelRequest($roomId,$senderId) {
        // Find the game invitation and cancel it
        $xoRoom = XORoom::where('id',$roomId)->where('sender_id', $senderId);
        $xoRoom->delete();

        return $xoRoom;
    }

    /**
     */
    public function AcceptInvite($roomId,$receiverId) {
        // Find the invitation and update its status to 'accepted'
        $xoRoom = XORoom::where('id', $roomId)->where('receiver_id', $receiverId);
        $xoRoom->status = 'accept';
        $xoRoom->save();

        return $xoRoom;
    }

    /**
     */
    public function cancelInvite($roomId, $receiverId) {
        // Find the invitation and update its status to 'rejected'
        $xoRoom = XORoom::where('id', $roomId)->where('receiver_id', $receiverId);
        $xoRoom->status = 'reject';
        $xoRoom->save();

        return $xoRoom;
    }
    /**
     *
     * @param mixed $receiverId
     */
    function listInvites($receiverId)
    {
        return XORoom::where('receiver_id', $receiverId)
            ->where('status', 'invited')
            ->get();
    }

    /**
     * @param mixed $senderId
     */
    function listRequests($senderId)
    {
        return XORoom::where('sender_id', $senderId)
            ->where('status', 'invited')
            ->get();
    }
}

