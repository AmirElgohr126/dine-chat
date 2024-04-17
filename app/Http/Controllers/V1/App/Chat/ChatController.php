<?php
namespace App\Http\Controllers\V1\App\Chat;

use App\Models\BanedChats;
use App\Models\PublicPlace;
use Exception;
use App\Models\Message;
use App\Models\Restaurant;
use App\Models\BookingDates;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\GeneralNotification;
use App\Http\Controllers\Controller;
use App\Service\ChatServices\ChatServiceInterface;
use App\Service\Notifications\NotificationInterface;
use App\Http\Requests\V1\App\Chat\RequestNewChatRequest;
use App\Http\Resources\V1\App\Chats\ConversationResource;

class ChatController extends Controller
{
    /**
     * Chat service instance.
     *
     * @var ChatServiceInterface
     */
    protected ChatServiceInterface $chatService;


    /**
     * Notification service instance.
     *
     * @var NotificationInterface
     */
    protected NotificationInterface $notification;

    /**
     * The place name for the chat.
     *
     * @var string
     */
    protected string $place;

    /**
     * The place id for the chat.
     *
     * @var int
     */
    protected int $placeId;



    public function __construct(ChatServiceInterface $chatService, NotificationInterface $notificationServices, Request $request)
    {
        $this->chatService = $chatService;
        $this->notification = $notificationServices;
        $this->setColumns($request);
    }

    /**
     * Set the place name and place id for the chat.
     *
     * @param  Request  $request
     * @return void
     */
    protected function setColumns(Request $request): void
    {
        $this->place = ($request->type == 'restaurant') ? 'restaurant_id' : 'public_place_id';
        $this->placeId = ($request->type == 'restaurant') ? $request->restaurant_id : $request->public_place_id;
    }
    /**
     * Get Chats for the authenticated user based on the restaurant.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getChats(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            // 2 - get conversation of his restaurant based on reservation
            $conversations = Conversation::
                where(function ($query) use ($user) {
                    $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
                })
                ->where($this->place, $this->placeId)
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


// ====================================================================================================

    /**
     * Send a chat request to another user for a specific restaurant.
     *
     * @param  RequestNewChatRequest  $request
     * @return JsonResponse
     */
    public function sendRequestChat(RequestNewChatRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
            // ====================================================================
            $this->chatService->chatWithYourSelf($user->id, $request->user_id);
            // ====================================================================
            $this->chatService->checkAnotherPersonInPlace($this->placeId, $request->user_id, $this->place);
            // ====================================================================
            $this->chatService->checkChatExist($user, $request, $this->place, $this->placeId);
            // ====================================================================
            $dataDeleted = $this->chatService->checkFollow($user, $request);
            // ====================================================================
            $chat = $this->chatService->createChat($dataDeleted, $this->place, $user, $request,$this->placeId);
            $message = Message::create([
                'conversation_id' => $chat->id,
                'sender_id' => $user->id,
                'content' => $request->message,
                'receiver_id' => getOtherUser($chat, $user->id),
                'replay_on' => null,
                'attachment' => null,
            ]);

            $receiverToken = $message->receiver->device_token;
            // if notification is 1 send notification else not
            if ($message->receiver->notification_status == 1) {
                $sender_name = "$user->first_name "." $user->last_name ";
                $this->notification->sendOneNotifyOneDevice([
                    'title' => "$sender_name" . 'send you new request Chat',
                    'message' => $message->content,
                    'image' => $message->attachment ?? null
                ], $receiverToken);
                GeneralNotification::requestChat($message->receiver, $sender_name, $message);
            }
            // Rest of your code to handle message creation and event dispatching...
            return finalResponse('success', 200, __('errors.success_request'));
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), null, null, $e->getMessage());
        }
    }


    /**
     * List chat requests sent by the authenticated user.
     * @param  Request  $request
     * @return JsonResponse
     */
    public function listRequestsChat(Request $request): JsonResponse
    {
        try {
            $per_page = $request->per_page ?? 10;
            $user = $request->user();
            $conversations = Conversation::where('sender_id', $user->id)
                ->where($this->place, $this->placeId)
                ->where('status', 'invited')
                ->where('deleted_at', '>', now())
                ->with([
                    'receiver',
                    'sender',
                    'messages' => function ($query) {
                        $query->lastMessage();
                    }
                ])
                ->withTrashed()
                ->paginate($per_page);
            if (!$conversations->items()) {
                throw new Exception(__('errors.No_conversation_found'), 204);
            }
            $conversations = ConversationResource::collection($conversations);
            $paginateConversation = pagnationResponse($conversations);
            return finalResponse('success', 200, $conversations->items(), $paginateConversation);
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), null, null, $e->getMessage());
        }
    }


    /**
     * Cancel a chat request for the authenticated user in a specific restaurant.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function cancelRequestChat(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $conversationId = $request->request_chat_id;
            $conversation = Conversation::where('id', $conversationId)
                ->where($this->place,$this->placeId)
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




// ====================================================================================================

    /**
     * List incoming chat requests for the authenticated user.
     * @param  Request  $request
     * @return JsonResponse
     */
    public function listInboxChat(Request $request): JsonResponse
    {
        try {
            $per_page = $request->per_page ?? 10;
            $user = $request->user();
            $conversations = Conversation::where('receiver_id', $user->id)
                ->where('status', 'invited')
                ->withTrashed()
                ->where('deleted_at', '>', now())
                ->with([
                    'sender',
                    'receiver',
                    'messages' => function ($query) {
                        $query->lastMessage();
                    }
                ])
                ->paginate($per_page);
            if ($conversations->isEmpty()) {
                throw new Exception(__('errors.No_conversation_found'), 204);
            }

            $conversations = ConversationResource::collection($conversations);
            $paginateConversation = pagnationResponse($conversations);

            return finalResponse('success', 200, $conversations->items(), $paginateConversation);
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), null, null, $e->getMessage());
        }
    }


    /**
     * Accept an incoming chat request for the authenticated user.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function acceptInboxChat(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'conversation_id' => ['required', 'exists:conversations,id']
        ]);
        try {
            $user = $request->user();
            $conversations = Conversation::where('receiver_id', $user->id)
                ->where('sender_id', $request->user_id)
                ->where($this->place, $this->placeId)
                ->where('status', 'invited')
                ->where('deleted_at', '>', now())
                ->withTrashed()
                ->first();
            if (!$conversations) {
                throw new Exception(__('errors.No_matching_conversation'), 405);
            }
            $conversations->update(['status' => 'accept']);
            if ($conversations->sender->notification_status == 1) {
                $receiverToken = $conversations->sender->device_token;
                $receiverName = $conversations->receiver->first_name . $conversations->receiver->last_name;
                $this->notification->sendOneNotifyOneDevice([
                    'title' => "$receiverName accept your chat request",
                    'message' => 'you can chat now',
                    'image' => ''
                ], $receiverToken);
                // have message
                $name = $conversations->receiver->first_name . ' ' . $conversations->receiver->last_name;
                GeneralNotification::acceptChat($conversations->sender, $name);

            }
            return finalResponse('success', 200, $conversations);
        } catch (Exception $e) {
            return finalResponse('failed', 500, null, null, $e->getMessage());
        }
    }


    /**
     * Reject an incoming chat request for the authenticated user.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function rejectInboxChat(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'conversation_id' => ['required', 'exists:conversations,id']
        ]);
        try {
            $user = $request->user();
            $conversation = Conversation::where('receiver_id', $user->id)
                ->where('sender_id', $request->user_id)
                ->where($this->place, $this->placeId)
                ->where('status', 'invited')
                ->where('deleted_at', '>', now())
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



// ====================================================================================================

    /**
     * ban user and chat
     * @param Request $request
     * @return JsonResponse
     */
    public function banChat(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'conversation_id' => ['required', 'exists:conversations,id']
        ]);
        $user = $request->user();
        $userBaned = $request->user_id;
        try {
            // Retrieve a ban record for the user in the specified conversation with 'ban' status
            $banRecord = BanedChats::firstOrCreate(
                [
                    'user_id' => $userBaned,
                    'conversation_id' => $request->conversation_id,
                    'status' => 'ban',
                ]
            );
            if (!$banRecord->wasRecentlyCreated) {
                throw new Exception(__('errors.User_already_baned'), 405);
            }
            return finalResponse('success', 200, $banRecord);
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), null, null, $e->getMessage());
        }
    }


    /**
     * unban user and chat
     * @param Request $request
     * @return JsonResponse
     */
    public function unBanChat(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'conversation_id' => ['required', 'exists:conversations,id']
        ]);
        $user = $request->user();
        $userBaned = $request->user_id;
        try {
            $banUser = BanedChats::where('user_id', $userBaned)
                ->where('conversation_id', $request->conversation_id)
                ->where('status', 'ban')
                ->first();
            if ($banUser) {
                $banUser->delete();
                return finalResponse('success', 200, $banUser);
            }
            throw new Exception(__('errors.No_matching_conversation'), 405);
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), null, null, $e->getMessage());
        }
    }

    /**
     * report user and chat
     * @param Request $request
     * @return JsonResponse
     */
    public function reportChat(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'conversation_id' => ['required', 'exists:conversations,id'],
            'reason' => 'required|string'
        ]);
        $user = $request->user();
        $userBaned = $request->user_id;
        try {
            $banUser = BanedChats::create([
                'user_id' => $userBaned,
                'conversation_id' => $request->conversation_id,
                'status' => 'report',
                'reason' => $request->reason
            ]);
            return finalResponse('success', 200, $banUser);
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), null, null, $e->getMessage());
        }
    }


}
