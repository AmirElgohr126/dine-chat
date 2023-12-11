<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\User;
use App\Models\UserFollower;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserFollower>
 */
class UserFollowerFactory extends Factory
{

    protected $model = UserFollower::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        $contact = Contact::inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'contact_id' => $contact->id,
            'follow_status' => $this->faker->randomElement(['follow', 'invited']),
        ];
    }
}
