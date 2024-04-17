<?php
namespace App\Http\Controllers\V1\App\Chat;

use Exception;
use App\Models\Message;
use App\Models\Restaurant;
use App\Events\MessageSent;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Service\Notifications\NotificationInterface;
use App\Http\Requests\V1\App\Chat\NewChatMessageRequest;
use App\Http\Requests\V1\App\Chat\UpdateChatMessageRequest;

class MessageController extends Controller
{

    /**
     * @var NotificationInterface
     */
    protected NotificationInterface $notification;

    /**
     * @var string
     */
    protected string $place;

    /**
     * @var int
     */
    protected int $placeId;

    public function __construct(NotificationInterface $notificationServices,Request $request)
    {
        $this->notification = $notificationServices;
        $this->setColumns($request);
    }

    /**
     * Set the place name and place id for the chat.
     * @param  Request  $request
     * @return void
     */
    protected function setColumns(Request $request): void
    {
        $this->place = ($request->type == 'restaurant') ? 'restaurant_id' : 'public_place_id';
        $this->placeId = ($request->type == 'restaurant') ? $request->restaurant_id : $request->public_place_id;
    }

    /**
     * Get all messages for a specific conversation
     * @param Request $request
     * @return JsonResponse
     */
    public function getMessages(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $per_page = $request->per_page ?? 10;
            $chat_id = $request->id;
            $conversation = Conversation::where('id', $chat_id)
                ->where(function ($query) use ($user) {
                    $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
                })
                ->where($this->place, $this->placeId)
                ->where('status', 'accept')
                ->where('deleted_at', '>', now())
                ->withTrashed()
                ->first();
            if (!$conversation) {
                throw new Exception(__('errors.not_found'), 404);
            }
            $messages = Message::where('conversation_id', '=', $conversation->id)->orderBy('created_at', 'desc')
                ->paginate($per_page);
            foreach ($messages as $message) {
                $message->sender_photo = retriveMedia() . $message->sender->photo;
                $message->receiver_photo = retriveMedia() . $message->receiver->photo;
                unset($message->sender, $message->receiver);
            }
            return finalResponse('success', 200, $messages->items());
        } catch (\Throwable $e) {
            return finalResponse('failed', 500, null, $e->getMessage());
        }
    }



    /**
     * Send a new message
     * @param NewChatMessageRequest $request
     * @return JsonResponse
     */
    public function sendMessage(NewChatMessageRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
            $id_replay = $request->replay_on;
            $conversation = Conversation::
                where('id', $request->id)->where(function ($query) use ($user) {
                    $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
                })
                ->where($this->place, $this->placeId)
                ->where('status', 'accept')
                ->withTrashed()
                ->where('deleted_at', '>', now())
                ->first();
            // ================================================================
            if (!$conversation) {
                throw new Exception(__("errors.No_conversation_found"), 404);
            }
            if ($request->hasFile('attachment')) {
                $media = $request->file('attachment');
                $pathmedia = storeFile($media, 'Chats/chat' . $conversation->id, 'public');
            }
            $check = $conversation->messages()->find($id_replay) ?? null;

            // Create a new message
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'content' => $request->message,
                'receiver_id' => getOtherUser($conversation, $user->id),
                'replay_on' => $check,
                'attachment' => isset($pathmedia) ? $pathmedia : null,
            ]);
            $sender_photo = retriveMedia() . $message->sender->photo;
            $receiver_photo = retriveMedia() . $message->receiver->photo;
            // $message = new MessageResource($message);
            MessageSent::dispatch($conversation->id, $message, $sender_photo, $receiver_photo);
            // send notification to reviver

            if($message->receiver->notification_status==1)
            {
                $receiverToken = $message->receiver->device_token;
                $sender_name = "$user->first_name"."$user->last_name";
                $this->notification->sendOneNotifyOneDevice([
                    'title' => $sender_name." send you new message ",
                    'message' => $message->content,
                    'image' => $message->attachment
                ],$receiverToken);
            }
            unset($message->receiver);
            unset($message->sender);
            return finalResponse('success', 200, $message);
        } catch (Exception $e) {
            return finalResponse('failed', 500, null, $e->getMessage());
        }
    }


    /**
     * Update a message
     * @param UpdateChatMessageRequest $request
     * @return JsonResponse
     */
    public function updateMessage(UpdateChatMessageRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
            $id_chat = $request->id;
            $id_message = $request->id_message;
            // ================================================================
            $conversation = Conversation::where('id', $id_chat)
                ->where(function ($query) use ($user) {
                    $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
                })
                ->where($this->place, $this->placeId)
                ->where('status', 'accept')
                ->withTrashed()
                ->where('deleted_at', '>', now())
                ->first();
            // ================================================================
            if (!$conversation) {
                throw new Exception(__('errors.No_conversation_found'), 404);
            }
            $message = $conversation->messages()->where('id', $id_message)->where('sender_id', $user->id)->first();
            if (!$message) {
                throw new Exception(__('errors.No_message_found'), 404);
            }
            $message->update([
                'content' => $request->message,
            ]);
            MessageSent::dispatch($conversation->id, $message);
            return finalResponse('success', 200, $message);
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), null, $e->getMessage());
        }
    }


    /**
     * Delete a message
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteMessage(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $id_chat = $request->id;
            $id_message = $request->id_message;
            // ================================================================
            $conversation = Conversation::where('id', $id_chat)
                ->where(function ($query) use ($user) {
                    $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
                })
                ->where($this->place, $this->placeId)
                ->where('status', 'accept')
                ->withTrashed()
                ->where('deleted_at', '>', now())
                ->first();
            // ================================================================
            if (!$conversation) {
                throw new Exception(__('errors.No_conversation_found'), 404);
            }
            $message = $conversation->messages()->where('id', $id_message)->where('sender_id', $user->id)->first();
            if (!$message) {
                throw new Exception(__('errors.No_message_found'), 404);
            }
            $message->delete();
            MessageSent::dispatch($conversation->id, $message);
            return finalResponse('success', 200, __('errors.message_was_deleted'));
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), null, $e->getMessage());
        }
    }


    /**
     * Delete a message
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAttachment(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $id_chat = $request->id;
            $id_message = $request->id_message;
            // ================================================================
            $conversation = Conversation::where('id', $id_chat)
                ->where(function ($query) use ($user) {
                    $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
                })
                ->where($this->place, $this->placeId)
                ->where('status', 'accept')
                ->withTrashed()
                ->where('deleted_at', '>', now())
                ->first();
            // ================================================================
            if (!$conversation) {
                throw new Exception(__('errors.No_conversation_found'), 404);
            }
            $message = $conversation->messages()->where('id', $id_message)->where('sender_id', $user->id)->first();
            if (!$message) {
                throw new Exception(__('errors.No_message_found'), 404);
            }
            $message->update([
                'attachment' => '',
            ]);
            return finalResponse('success', 200, __('errors.message_was_deleted'));
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), null, $e->getMessage());
        }
    }
}


