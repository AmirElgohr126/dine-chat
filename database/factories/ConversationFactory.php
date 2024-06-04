<?php

namespace Database\Factories;

use App\Models\Conversation;
use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;


class ConversationFactory extends Factory
{


    protected $model = Conversation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'sender_id' => User::inRandomOrder()->first()->id,
        'receiver_id' => User::inRandomOrder()->first()->id,
        'restaurant_id' => Restaurant::inRandomOrder()->first()->id,
        'status' => $this->faker->randomElement(['accept', 'reject']),
        ];
    }
}
