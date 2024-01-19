<?php

namespace App\Http\Controllers\App\XOgame;

use App\Models\User;
use App\Models\XOGame;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Service\XoServices\XoGameServices;
use App\Service\Notifications\NotificationServices;



class GameController extends Controller
{
    protected $xoGameServices;

    public function __construct(XoGameServices $xoGameServices)
    {
        $this->xoGameServices = $xoGameServices;
    }

    public function start()
    {
        $game = new XOGame();
        $game->save();
        return response()->json($game);
    }

    public function move(Request $request, XOGame $game)
    {
        $validated = $request->validate([
            'row' => 'required|integer|min:0|max:2',
            'col' => 'required|integer|min:0|max:2',
        ]);

        $row = $validated['row'];
        $col = $validated['col'];

        // Assuming makeMove returns a status or boolean to indicate success or failure
        $moveResult = $game->makeMove($row, $col);

        if ($moveResult) {
            return response()->json([
                'success' => true,
                'game' => $game
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid move or game is finished'
            ], 400);
        }
    }

    public function RequestToPlay(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|integer',
            'restaurant_id' => 'required|integer',
        ]);
        $userId = $request->user()->id;
        $response = $this->xoGameServices->RequestToPlay(
            $userId,
            $validated['receiver_id'],
            $validated['restaurant_id']
        );

        $notification = new NotificationServices;
        $notification->sendOneNotifyOneDevice([
            'title' => 'someone need to play XO with you ',
            'message'=> $request->user()->name . "can you play with me?"
        ],$request->user()->device_token);

        return finalResponse('success', 200, $response);
    }

    public function cancelRequest(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|integer',
        ]);

        $response = $this->xoGameServices->cancelRequest($validated['room_id'], $request->user()->id);

        return finalResponse('success', 200, $response);
    }

    public function AcceptInvite(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|integer',
        ]);
        $response = $this->xoGameServices->AcceptInvite($validated['room_id'], $request->user()->id);
        $user = User::find($response->sender_id);
        $notification = new NotificationServices;
        $notification->sendOneNotifyOneDevice([
            'title' => 'request XO accepted ',
            'message' =>  "request to " . $request->user()->name .'accepted'
        ], $user->device_token);

        return finalResponse('success', 200, $response);
    }

    public function cancelInvite(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|integer',
        ]);

        $response = $this->xoGameServices->cancelInvite($validated['room_id'], $request->user()->id);

        return finalResponse('success', 200, $response);
    }

    public function listInvites(Request $request)
    {
        $userId = $request->user()->id; // Assuming user authentication
        $invites = $this->xoGameServices->listInvites($userId);
        return finalResponse('success', 200, $invites);
    }

    public function listRequests(Request $request)
    {
        $userId = $request->user()->id; // Assuming user authentication
        $requests = $this->xoGameServices->listRequests($userId);
        return finalResponse('success', 200, $requests);
    }

}
