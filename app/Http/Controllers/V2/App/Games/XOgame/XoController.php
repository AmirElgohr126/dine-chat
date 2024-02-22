<?php

namespace App\Http\Controllers\V2\App\Games\XOgame;

use App\Models\XOGame;
use Illuminate\Http\Request;
use App\Events\XoGame as XoEvent;
use Illuminate\Routing\Controller;
use App\Http\Resources\V1\App\Games\GameStateResource;


class XoController extends Controller
{

    public static function start($room)
    {
        return self::intailBoard($room);
    }

    public static function intailBoard($room)
    {
        $board = [
            ['', '', ''],
            ['', '', ''],
            ['', '', '']
        ];
        $current_player = 'X';
        $room_id = $room->id;
        $player_x_id = $room->sender_id;
        $player_o_id = $room->receiver_id;
        $status = 'active';
        $game = XOGame::create(
            [
                'board' => $board,
                'current_player' => $current_player,
                'status' => $status,
                'player_x_id' => $player_x_id,
                'player_o_id' => $player_o_id,
                'room_id'=> $room_id
            ]);

            return $game;
    }


    public function move(Request $request)
    {
        $validated = $request->validate([
            'row' => 'required|integer|min:0|max:2',
            'col' => 'required|integer|min:0|max:2',

        ]);
        $row = $validated['row'];
        $col = $validated['col'];
        $board = XOGame::find($request->game);

        $moveResult = $this->makeMove($row, $col,$board); // return true or false


        if ($moveResult) {
            $moveResult->playerX;
            $moveResult->playerO;
            $game = new GameStateResource($moveResult);
            XoEvent::dispatch($game);
            return finalResponse('success',200, $game);
        } else {
            return finalResponse('failed', 400,"can't play ");
        }
    }



    public function makeMove($row, $col,$board)
    {
        $current_player = $board->current_player;
        $status = $board->status;
        $boardPlayOn = $board->board;
        // Check if the move is valid and make the move
        if ($boardPlayOn[$row][$col] == '' && $status == 'active') {
            $boardPlayOn[$row][$col] = $current_player;

            $board->board = $boardPlayOn;
            $result = $this->checkWinner($board->board);
            $board->status = $result['status'];
            if ($result['status'] === 'finished') {
                $board->winner = $result['winner'];
            }
            $board->current_player = $this->switchTurn($current_player);
            $board->save();
            return $board;
        }
        return false;
    }




    private function checkWinner($board)
    {
        // Checking rows and columns for a win
        for ($i = 0; $i < 3; $i++) {
            if ($board[$i][0] != '' && $board[$i][0] === $board[$i][1] && $board[$i][1] === $board[$i][2]) {
                return ['status' => 'finished', 'winner' => $board[$i][0]];
            }

            if ($board[0][$i] != '' && $board[0][$i] === $board[1][$i] && $board[1][$i] === $board[2][$i]) {
                return ['status' => 'finished', 'winner' => $board[0][$i]];
            }
        }

        // Checking diagonals for a win
        if ($board[0][0] != '' && $board[0][0] === $board[1][1] && $board[1][1] === $board[2][2]) {
            return ['status' => 'finished', 'winner' => $board[0][0]];
        }
        if ($board[0][2] != '' && $board[0][2] === $board[1][1] && $board[1][1] === $board[2][0]) {
            return ['status' => 'finished', 'winner' => $board[0][2]];
        }

        // Checking for a draw
        if ($this->isBoardFull($board)) {
            return ['status' => 'draw', 'winner' => ''];
        }

        // Game is still active
        return ['status' => 'active', 'winner' => ''];
    }

    private function isBoardFull($board)
    {
        foreach ($board as $row) {
            if (in_array('', $row)) {
                return false;
            }
        }
        return true;
    }

    private function switchTurn($current_player)
    {
        return $current_player == 'X' ? 'O' : 'X';
    }



    public function getBoard(Request $request)
    {
        $request->validate([
            'game_id' => ['required','exists:x_o_games,id'],
        ]);

        $user = $request->user();
        $game = XOGame::find($request->game_id);
        if($user->id == $game->player_x_id || $user->id == $game->player_o_id)
        {
            $game->playerX;
            $game->playerO;
            $game = new GameStateResource($game);

            return finalResponse('success',200,$game);
        }
        return finalResponse('failed',400,'game not found');
    }




}
