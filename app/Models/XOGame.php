<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XOGame extends Model
{
    use HasFactory;

    protected $fillable = ['board', 'current_player', 'status', 'player_x_id', 'player_o_id'];

    protected $casts = [
        'board' => 'array'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->board = [['', '', ''],
                        ['', '', ''],
                        ['', '', '']];
        $this->current_player = 'X';
        $this->status = 'active'; // active, finished, tie
    }

    public function makeMove($row, $col)
    {
        $board = $this->board;

        // Check if the move is valid and make the move
        if ($board[$row][$col] == '' && $this->status == 'active') {
            $board[$row][$col] = $this->current_player;

            // Set the modified board back to the attribute
            $this->board = $board;

            $this->checkWinner();
            $this->switchTurn();

            // Save the changes to the database
            $this->save();

            return true;
        }

        return false;
    }

    private function checkWinner()
    {
        // Checking rows and columns
        for ($i = 0; $i < 3; $i++) {
            if ($this->board[$i][0] === $this->board[$i][1] && $this->board[$i][1] === $this->board[$i][2] && $this->board[$i][0] != '') {
                $this->status = 'finished';
                return 1;
            }

            if ($this->board[0][$i] === $this->board[1][$i] && $this->board[1][$i] === $this->board[2][$i] && $this->board[0][$i] != '') {
                $this->status = 'finished';
                return 1;
            }
        }

        // Checking diagonals
        if (
            $this->board[0][0] === $this->board[1][1] && $this->board[1][1] === $this->board[2][2] && $this->board[0][0] != '' ||
            $this->board[0][2] === $this->board[1][1] && $this->board[1][1] === $this->board[2][0] && $this->board[0][2] != ''
        ) {
            $this->status = 'finished';
            return 1;
        }

        // Checking for a tie
        if ($this->isBoardFull()) {
            $this->status = 'tie';
        }
    }

    private function isBoardFull()
    {
        foreach ($this->board as $row) {
            if (in_array('', $row)) {
                return false;
            }
        }
        return true;
    }


    private function switchTurn()
    {
        $this->current_player = $this->current_player == 'X' ? 'O' : 'X';
        $this->save();
    }

}
