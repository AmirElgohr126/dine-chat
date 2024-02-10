<?php
namespace App\Http\Controllers\App\Games;

use App\Models\User;
use App\Events\XoRoom;

use App\Models\XOGame;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\XoGame as EventsXoGame;
use App\Http\Resources\Games\RoomResource;
use App\Service\GamesServices\GameServices;
use App\Http\Resources\Games\GameStateResource;
use App\Http\Resources\Games\InvitationResource;
use App\Service\Notifications\NotificationServices;
use App\Http\Controllers\App\Games\XOgame\XoController;
use App\Http\Controllers\App\Games\CheesGame\ChessController;

class GameController extends Controller
{
    protected $gameServices;

    public function __construct(GameServices $gameServices)
    {
        $this->gameServices = $gameServices;
    }



    public function RequestToPlay(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|integer|exists:users,id',
            'type_room' => 'required|in:chess,xo',
        ]);
        $userId = $request->user()->id;
        $room = $this->gameServices->RequestToPlay($userId, $request->receiver_id, $request->restaurant_id, $request->type_room);
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

    public function cancelRequest(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|integer',
        ]);
        $room = $this->gameServices->cancelRequest($validated['room_id'], $request->user()->id);
        return finalResponse('success', 200, $room);
    }


    public function AcceptInvite(Request $request)
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

    public function cancelInvite(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|integer'
        ]);
        $room = $this->gameServices->cancelInvite($validated['room_id'], $request->user()->id);
        XoRoom::dispatch($room, 0);
        return finalResponse('success', 200, $room);
    }

    public function listInvites(Request $request)
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
