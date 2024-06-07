<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{

    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'conversation_id' => Conversation::inRandomOrder()->first()->id,
            'sender_id' => User::inRandomOrder()->first()->id,
            'content' => $this->faker->paragraph,
            'attachment' => $this->faker->imageUrl(),
            'receiver_id' => User::inRandomOrder()->first()->id,
            'replay_on' => null, // Adjust as needed
        ];
    }
}
