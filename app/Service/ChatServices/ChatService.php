<?php
namespace App\Service\ChatServices;

use Exception;
use App\Models\Conversation;
use App\Models\UserFollower;
use App\Models\UserAttendance;
use App\Service\ChatServices\ChatServiceInterface;



class ChatService implements ChatServiceInterface
{

    /**
     *
     * @param mixed $authId
     * @param mixed $IdChatWith
     */
    public function chatWithYourSelf($authId, $IdChatWith) {
        if ($authId == $IdChatWith) {
            throw new Exception(__('errors.can_not_chat_with_yourself'), 405);
        }
    }
    /**
     *
     * @param mixed $restaurantId
     * @param mixed $IdChatWith
     */
    public function checkAnotherPersonInRestaurant($restaurantId, $IdChatWith) {
        $anotherUserAttendance = UserAttendance::where('user_id', $IdChatWith)
            ->where('created_at', '>', now()->subHour())
            ->where('restaurant_id', $restaurantId)
            ->first();
        if (!$anotherUserAttendance) {
            throw new Exception(__('errors.user_not_in_restaurant'), 405);
        }
    }
    /**
     *
     * @param mixed $user
     * @param mixed $request
     * @param mixed $restaurant
     */
    public function checkChatExist($user, $request, $restaurant) {
        $existingConversation = Conversation::
            where(function ($query) use ($user, $request, $restaurant) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $request->user_id)
                    ->where('restaurant_id', $restaurant->id)
                    ->where(function ($q) {
                        $q->where('status', 'invited')
                            ->orWhere('status', 'accept');
                    })
                    ->where('deleted_at', '>', now());
            })
            ->orWhere(function ($query) use ($user, $request, $restaurant) {
                $query->where('sender_id', $request->user_id)
                    ->where('receiver_id', $user->id)
                    ->where('restaurant_id', $restaurant->id)
                    ->where(function ($q) {
                        $q->where('status', 'invited')
                            ->orWhere('status', 'accept');
                    })
                    ->where('deleted_at', '>', now());
            })
            ->withTrashed()
            ->first();

        if ($existingConversation) {
            throw new Exception(__('errors.make_request_before'), 405);
        }
    }
    /**
     *
     * @param mixed $user
     * @param mixed $request
     */
    public function checkFollow($user, $request, $restaurant) {
        $checkFollow = UserFollower::where('user_id', $user->id)
            ->where('followed_user', $request->user_id)
            ->where('follow_status', 'follow')
            ->first();
        if ($checkFollow) {
            $dataDeleted = determainPeriod($restaurant);
        }
    }

}

?>
