<?php

namespace App\Http\Resources\Games;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameStateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'board' => $this->board,
            'current_player' => $this->current_player,
            'status' => $this->status,
            'player_x' => [
                'id' => (int) $this->player_x_id,
                'first_name' => $this->player_x->first_name,
                'last_name' => $this->player_x->last_name,
                'photo' => retriveMedia(). $this->player_x->photo,
            ],
            'player_o' => [
                'id' => (int) $this->player_o_id,
                'first_name' => $this->player_o->first_name,
                'last_name' => $this->player_o->last_name,
                'photo' => retriveMedia(). $this->player_o->photo,
            ],
            'room_id' => $this->room_id,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
