<?php

namespace App\Http\Controllers\App\Games\XOgame;

use App\Events\XoGame as XoEvent;
use App\Models\XOGame;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


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
            XoEvent::dispatch($moveResult);
            return finalResponse('success',200,$moveResult);
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
            $board->status = $this->checkWinner($board->board);
            $board->current_player = $this->switchTurn($current_player);
            $board->save();
            return $board;
        }
        return false;
    }




    private function checkWinner($board)
    {
        // Checking rows and columns
        for ($i = 0; $i < 3; $i++) {
            if ($board[$i][0] === $board[$i][1] && $board[$i][1] === $board[$i][2] && $board[$i][0] != '') {
                return $status = 'finished';
            }

            if ($board[0][$i] === $board[1][$i] && $board[1][$i] === $board[2][$i] && $board[0][$i] != '') {
                return $status = 'finished';
            }
        }

        // Checking diagonals
        if ($board[0][0] === $board[1][1] && $board[1][1] === $board[2][2] && $board[0][0] != '' ||
            $board[0][2] === $board[1][1] && $board[1][1] === $board[2][0] && $board[0][2] != '')
        {
            return $status = 'finished';
        }
        // Checking for a draw
        if ($this->isBoardFull($board)) {
            return $status = 'draw';
        }
        return $status = 'active';
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








}
