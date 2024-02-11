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
            'winner' => $this->winner,
            'player_x' => [
                'id' => (int) $this->playerX->id,
                'first_name' => $this->playerX->first_name,
                'last_name' => $this->playerX->last_name,
                'photo' => retriveMedia(). $this->playerX->photo,
            ],
            'player_o' => [
                'id' => (int) $this->playerO->id,
                'first_name' => $this->playerO->first_name,
                'last_name' => $this->playerO->last_name,
                'photo' => retriveMedia(). $this->playerO->photo,
            ],
            'room_id' => (int) $this->room_id,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
