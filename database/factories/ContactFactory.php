<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{

    protected $model = Contact::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        return [
            'user_id' =>  $user->id,
            'name' => $this->faker->name,
            'photo' => $this->faker->imageUrl(null, 200, 200),
            'phone' => $this->faker->phoneNumber,
            'status_on_app' => $this->faker->randomElement(['subscrib', 'not_subscrib']),
        ];
    }
}
