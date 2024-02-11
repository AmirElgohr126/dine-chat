<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XOGame extends Model
{
    use HasFactory;

    protected $fillable = ['board', 'current_player', 'status', 'player_x_id', 'player_o_id','room_id','winner'];

    protected $casts = [
        'board' => 'array'
    ];

    public function playerX()
    {
        return $this->belongsTo(User::class, 'player_x_id', 'id')->select(
            [
                'id',
                'first_name',
                'last_name',
                'photo'
            ]
        );
    }
    public function playerO()
    {
        return $this->belongsTo(User::class, 'player_o_id', 'id')->select(
            [
                'id',
                'first_name',
                'last_name',
                'photo'
            ]
        );
    }


}
