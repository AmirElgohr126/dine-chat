<?php
namespace App\Http\Controllers\V1\App\Games;

use App\Models\User;
use App\Events\XoRoom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\XoGame as EventsXoGame;
use App\Service\GamesServices\GameServices;
use App\Http\Resources\V1\App\Games\RoomResource;
use App\Service\Notifications\NotificationServices;
use App\Http\Resources\V1\App\Games\GameStateResource;
use App\Http\Resources\V1\App\Games\InvitationResource;
use App\Http\Controllers\V1\App\Games\XOgame\XoController;
use App\Http\Controllers\V1\App\Games\CheesGame\ChessController;

class GameController extends Controller
{
    /**
     * @var GameServices
     */
    protected GameServices $gameServices;


    /**
     * @var string
     */
    protected string $place;


    /**
     * @var int
     */
    protected int $placeId;


    /**
     * GameController constructor.
     * @param GameServices $gameServices
     * @param Request $request
     */
    public function __construct(GameServices $gameServices, Request $request)
    {
        $this->gameServices = $gameServices;
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
     * Request to play
     * @param Request $request
     * @return JsonResponse
     */
    public function requestToPlay(Request $request): JsonResponse
    {
        $request->validate([
            'receiver_id' => 'required|integer|exists:users,id',
            'type_room' => 'required|in:chess,xo',
        ]);
        $userId = $request->user()->id;
        $room = $this->gameServices->RequestToPlay($userId, $request->receiver_id, $this->placeId,$this->place, $request->type_room);
        $room->sender;
        $room->receiver;

        $device_token = User::find($request->receiver_id)->device_token;
        $notification = new NotificationServices;
        $notification->sendOneNotifyOneDevice([
            'title' => 'someone need to play with you ',
            'message' => $request->user()->name . "can you play with me?",
            'image' => ''
        ], $device_token);

        $room = new InvitationResource($room);
        return finalResponse('success', 200, $room);
    }



    /**
     * Cancel the request to play
     * @param Request $request
     * @return JsonResponse
     */
    public function cancelRequest(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'room_id' => 'required|integer',
        ]);
        $room = $this->gameServices->cancelRequest($validated['room_id'], $request->user()->id);
        return finalResponse('success', 200, $room);
    }


    /**
     * Accept the invitation to play
     * @param Request $request
     * @return JsonResponse
     */
    public function AcceptInvite(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'room_id' => 'required|integer|exists:rooms,id',
        ]);

        $room = $this->gameServices->AcceptInvite($validated['room_id'], $request->user()->id);

        switch ($room->type_room) {
            case 'chess':
                $game = ChessController::start($room);
                break;
            case 'xo':
                $game = XoController::start($room);
                $game->playerX;
                $game->playerO;
                XoRoom::dispatch($room, $game->id);
                $game = new GameStateResource($game);
                EventsXoGame::dispatch($game);
                break;
        }

        $sender = User::find($room->sender_id);
        $notification = new NotificationServices;
        $notification->sendOneNotifyOneDevice([
            'title' => 'request play accepted ',
            'message' => "request to " . $request->user()->name . 'accepted',
            'image' => ''
        ], $sender->device_token);

        return finalResponse('success', 200, $game);
    }


    /**
     * Reject the invitation to play
     * @param Request $request
     * @return JsonResponse
     */
    public function cancelInvite(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'room_id' => 'required|integer'
        ]);
        $room = $this->gameServices->cancelInvite($validated['room_id'], $request->user()->id);
        XoRoom::dispatch($room, 0);
        return finalResponse('success', 200, $room);
    }

    /**
     * List the invites
     * @param Request $request
     * @return JsonResponse
     */
    public function listInvites(Request $request): JsonResponse
    {
        $userId = $request->user()->id; // Assuming user authentication
        $invites = $this->gameServices->listInvites($userId);
        $invites->load('receiver');
        $invites->load('sender');
        $invites = RoomResource::collection($invites);
        return finalResponse('success', 200, $invites);
    }

    // public function listRequests(Request $request)
    // {
    //     $userId = $request->user()->id; // Assuming user authentication
    //     $requests = $this->gameServices->listRequests($userId);
    //     return finalResponse('success', 200, $requests);
    // }
}
