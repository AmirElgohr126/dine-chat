<?php
namespace App\Http\Controllers\App\Chat;

use Exception;
use App\Models\Message;
use App\Models\Restaurant;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\RequestNewChatRequest;
use App\Http\Resources\Chats\ConversationResource;
use App\Models\GeneralNotification;
use App\Service\ChatServices\ChatServiceInterface;
use App\Service\Notifications\NotificationInterface;

class ChatController extends Controller
{
    protected $chatService;
    protected $notification;
    public function __construct(ChatServiceInterface $chatService, NotificationInterface $notificationServices)
    {
        $this->chatService = $chatService;
        $this->notification = $notificationServices;
    }

    /**
     * Get Chats for the authenticated user based on the restaurant.
     *
     * This function retrieves conversations for the authenticated user in a specific restaurant
     * where the status is 'accept' and the conversations are not marked as deleted. It also
     * filters out conversations without any messages. The result includes the receiver information
     * and the last message for each conversation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChats(Request $request)
    {
        try {
            $user = $request->user();
            // 2 - get conversation of his reastaurant based on reservation
            $conversations = Conversation::
                where(function ($query) use ($user)
                    {
                        $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
                    })
                ->where('restaurant_id', $request->restaurant_id)
                ->where('status', 'accept')
                ->where('deleted_at', '>', now())
                ->withTrashed()
                ->with([
                    'receiver',
                    'sender',
                    'messages' => function ($query) {
                        $query->lastMessage();
                    },
                ])
                ->get();
            $conversations = ConversationResource::collection($conversations);
            return finalResponse('success', 200, $conversations);
        } catch (Exception $e) {
            return finalResponse('failed', 500, null, null, $e->getMessage());
        }
    }

    /**
     * Send a chat request to another user for a specific restaurant.
     *
     * This function handles the process of sending a chat request to another user for a given restaurant.
     * It checks if the user is attempting to chat with themselves, if a conversation already exists,
     * and if the corresponding user is in reservation at the specified restaurant. If the user is followed,
     * it determines the deletion period. It then creates a new conversation request and handles necessary
     * events for the receiver, such as notifying them to accept or reject the request.
     *
     * @param  \App\Http\Requests\Chat\RequestNewChatRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendRequestChat(RequestNewChatRequest $request)
    {
        try {
            $user = $request->user();
            $this->chatService->chatWithYourSelf($user->id, $request->user_id);
            $restaurant = Restaurant::find($request->restaurant_id);
            $this->chatService->checkAnotherPersonInRestaurant($request->restaurant_id, $request->user_id);
            $this->chatService->checkChatExist($user, $request, $restaurant);
            $dataDeleted = $this->chatService->checkFollow($user, $request, $restaurant);
            $request_reservation = Conversation::create([
                'sender_id' => $user->id,
                'receiver_id' => $request->user_id,
                'restaurant_id' => $restaurant->id,
                'status' => 'invited',
                'deleted_at' => $dataDeleted ?? now()->addHour()
            ]);
            $message = Message::create([
                'conversation_id' => $request_reservation->id,
                'sender_id' => $user->id,
                'content' => $request->message,
                'receiver_id' => getOtherUser($request_reservation, $user->id),
                'replay_on' => null,
                'attachment' => null,
            ]);

            $receiverToken = $message->receiver->device_token;
            if($message->receiver->notification_status==1)
            {
                $sender_name = "$user->first_name" .' '. "$user->last_name";
                $this->notification->sendOneNotifyOneDevice([
                    'title' => "$sender_name" . 'send you new request Chat',
                    'message' => $message->content,
                    'photo' => $message->attachment ?? null
                ], $receiverToken);
                GeneralNotification::requestChat($message->receiver,$sender_name,$message);
            }
            // Rest of your code to handle message creation and event dispatching...
            return finalResponse('success', 200, __('errors.success_request'));
        } catch (Exception $e) {
            return finalResponse('failed', 500, null, null, $e->getMessage());
        }
    }

    /**
     * List chat requests for the authenticated user in a specific restaurant.
     *
     * This function retrieves chat requests for the authenticated user in a specified restaurant,
     * where the status is 'invited' and the conversations are not marked as deleted. It paginates
     * the results based on the specified number of items per page and includes receiver information.
     * If no conversations are found, it throws a 204 No Content exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listRequestsChat(Request $request)
    {
        try {
            $per_page = $request->per_page ?? 10 ;
            $user = $request->user();
            $conversations = Conversation::where('sender_id', $user->id)
                ->where('restaurant_id', $request->restaurant_id)
                ->where('status','invited')
                ->where('deleted_at','>', now())
                ->with(['receiver', 'sender',
                    'messages' => function ($query) {
                        $query->lastMessage();
                    }])
                ->withTrashed()
                ->paginate($per_page);
            if (!$conversations->items()) {
                throw new Exception(__('errors.No_conversation_found'), 204);
            }
            $conversations = ConversationResource::collection($conversations);
            $pagnateConversation = pagnationResponse($conversations);
            return finalResponse('success', 200, $conversations->items(), $pagnateConversation);
        } catch (Exception $e) {
            return finalResponse('failed',$e->getCode(),null,null,$e->getMessage());
        }
    }

    /**
     * Cancel a chat request for the authenticated user in a specific restaurant.
     *
     * This function cancels a chat request initiated by the authenticated user for a given restaurant.
     * It retrieves the conversation based on the provided conversation ID, restaurant ID, and sender ID,
     * ensuring that the conversation is in the 'invited' status and not marked as deleted. If no matching
     * conversation is found, it throws a 405 Method Not Allowed exception. Otherwise, it updates the
     * conversation status to 'reject,' indicating the cancellation of the chat request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelRequestChat(Request $request)
    {
        try {
            $user = $request->user();
            $conversationId = $request->request_chat_id;
            $conversation = Conversation::where('id', $conversationId)
                ->where('restaurant_id', $request->restaurant_id)
                ->where('sender_id', $user->id)
                ->where('status', 'invited')
                ->withTrashed()
                ->where('deleted_at', '>', now())
                ->first();
            if (!$conversation) {
                throw new Exception(__('errors.No_request_matching'), 405);
            }
            // Update the conversation status to 'canceled'
            $conversation->update(['status' => 'reject']);

            return finalResponse('success', 200);
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), null, null, $e->getMessage());
        }
    }

    /**
     * List incoming chat requests for the authenticated user.
     *
     * This function retrieves incoming chat requests for the authenticated user where the status is 'invited'
     * and the conversations are not marked as deleted. It paginates the results based on the specified number
     * of items per page and includes sender information. If no incoming chat requests are found, it throws a
     * 204 No Content exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listInboxChat(Request $request)
    {
        try {
            $per_page = $request->per_page ?? 10;
            $user = $request->user();
            $conversations = Conversation::where('receiver_id', $user->id)
                ->where('status', 'invited')
                ->withTrashed()
                ->where('deleted_at', '>', now())
                ->with(['sender', 'receiver',
                    'messages' => function ($query) {
                        $query->lastMessage();
                    }])
                ->paginate($per_page);
            if ($conversations->isEmpty()) {
                throw new Exception(__('errors.No_conversation_found'), 204);
            }

            $conversations = ConversationResource::collection($conversations);
            $pagnateConversation = pagnationResponse($conversations);

            return finalResponse('success', 200, $conversations->items(), $pagnateConversation);
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), null, null, $e->getMessage());
        }
    }
    /**
     * Accept an incoming chat request for the authenticated user.
     *
     * This function allows the authenticated user to accept an incoming chat request from another user for a
     * specific restaurant. It validates the request parameters, including the user ID and conversation ID. It then
     * retrieves the corresponding conversation and checks if it meets the criteria for acceptance (status is 'invited'
     * and not marked as deleted within the last hour). If a matching conversation is found, it updates the status to
     * 'accept.' Otherwise, it throws a 405 Method Not Allowed exception indicating that no matching conversation was found.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function AcceptinboxChat(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'conversation_id' => ['required','exists:conversations,id']
        ]);
        try {
            $user = $request->user();
            $conversations = Conversation::where('receiver_id', $user->id)
                ->where('sender_id', $request->user_id)
                ->where('restaurant_id', $request->restaurant_id)
                ->where('status', 'invited')
                ->where('deleted_at', '>', now()->subHour())
                ->withTrashed()
                ->first();
                if(!$conversations)
                {
                    throw new Exception(__('errors.No_matching_conversation'), 405);
                }
                $conversations->update(['status' => 'accept']);
                if($conversations->sender->notification_status==1)
                {
                    $receiverToken = $conversations->sender->device_token;
                    $this->notification->sendOneNotifyOneDevice([
                        'title' => 'your request Chat is accepted',
                        'message' => 'you can chat now',
                        'photo' => ''
                    ], $receiverToken);
                    // have message
                    $name = $conversations->receiver->first_name .' '. $conversations->receiver->last_name;
                GeneralNotification::acceptChat($conversations->sender, $name);

            }
                return finalResponse('success', 200, $conversations);
        } catch (Exception $e) {
            return finalResponse('failed', 500,null,null,$e->getMessage());
        }
    }
    /**
     * Reject an incoming chat request for the authenticated user.
     *
     * This function allows the authenticated user to reject an incoming chat request from another user for a specific
     * restaurant. It validates the request parameters, including the user ID and conversation ID. It then retrieves the
     * corresponding conversation and checks if it meets the criteria for rejection (status is 'invited' and not marked
     * as deleted within the last hour). If a matching conversation is found, it updates the status to 'reject.' Otherwise,
     * it throws a 405 Method Not Allowed exception indicating that no matching conversation was found.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function RejectinboxChat(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'conversation_id' => ['required', 'exists:conversations,id']
        ]);
        try {
            $user = $request->user();
            $conversation = Conversation::where('receiver_id', $user->id)
                ->where('sender_id', $request->user_id)
                ->where('restaurant_id', $request->restaurant_id)
                ->where('status', 'invited')
                ->where('deleted_at', '>', now()->subHour())
                ->withTrashed()
                ->first();
            if ($conversation) {
                $conversation->update(['status' => 'reject']);
                return finalResponse('success', 200, $conversation);
            }
            throw new Exception(__('errors.No_matching_conversation'), 405);
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), null, null, $e->getMessage());
        }
    }




}

?>
