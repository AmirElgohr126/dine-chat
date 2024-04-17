<?php
namespace App\Service\ChatServices;

use Exception;
use App\Models\BookingDates;
use App\Models\Conversation;
use App\Models\UserFollower;
use App\Models\UserAttendance;
use App\Models\ConversationDeleteAfter;
use App\Service\ChatServices\ChatServiceInterface;



class ChatService implements ChatServiceInterface
{

    /**
     * check chat with your self
     * @param mixed $authId
     * @param mixed $IdChatWith
     * @throws Exception
     */
    public function chatWithYourSelf($authId, $IdChatWith): void
    {
        if ($authId == $IdChatWith) {
            throw new Exception(__('errors.can_not_chat_with_yourself'), 201);
        }
    }


    /**
     * check corresponding user is in reservation in this restaurant or no
     * @param mixed $PlaceId
     * @param mixed $IdChatWith
     * @param mixed $place
     * @throws Exception
     */
    public function checkAnotherPersonInPlace($PlaceId, $IdChatWith, $place): void
    {
            $anotherUserAttendance = UserAttendance::where('user_id', $IdChatWith)
                ->where($place, $PlaceId)
                ->where('created_at', '>', now())
                ->first();
            if (!$anotherUserAttendance) {
                throw new Exception(__('errors.user_not_in_the_same_place'), 201);
            }
    }


    /**
     * Handle the case where the conversation already exists
     * @param mixed $user
     * @param mixed $request
     * @param mixed $place
     * @param mixed $placeId
     * @throws Exception
     */
    public function checkChatExist($user, $request, $place , $placeId): void
    {
        $existingConversation = Conversation::
            where(function ($query) use ($user, $request, $place, $placeId) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $request->user_id)
                    ->where($place , $placeId)
                    ->where(function ($q) {
                        $q->where('status', 'invited')
                            ->orWhere('status', 'accept');
                    })
                    ->where('deleted_at', '>', now());
            })
            ->orWhere(function ($query) use ($user, $request, $place , $placeId) {
                $query->where('sender_id', $request->user_id)
                    ->where('receiver_id', $user->id)
                    ->where($place , $placeId)
                    ->where(function ($q) {
                        $q->where('status', 'invited')
                            ->orWhere('status', 'accept');
                    })
                    ->where('deleted_at', '>', now());
            })
            ->withTrashed()
            ->first();

        if ($existingConversation) {
            throw new Exception(__('errors.make_request_before'), 201);
        }
    }



    /**
     * Handle the case if follow or no
     * @param mixed $user
     * @param mixed $request
     * @return mixed
     */
    public function checkFollow($user, $request): mixed
    {
        $dataDeleted = ConversationDeleteAfter::firstRowNormalCase();
        $checkFollow = UserFollower::where('user_id', $user->id)
            ->where('followed_user', $request->user_id)
            ->where('follow_status', 'follow')
            ->first();
        if ($checkFollow) {
            $dataDeleted = ConversationDeleteAfter::firstRowForFollower();
        }
        return $dataDeleted;
    }

    /**
     * create chat
     * @param mixed $dataDeleted
     * @param mixed $place
     * @param mixed $user
     * @param mixed $request
     * @param mixed $placeId
     * @return mixed
     */
    public function createChat($dataDeleted, $place, $user, $request,$placeId): mixed
    {
        $request_reservation = Conversation::create([
            'sender_id' => $user->id,
            'receiver_id' => $request->user_id,
             $place => $placeId,
            'status' => 'invited',
            'deleted_at' => $dataDeleted
        ]);
        return $request_reservation;
    }
}

