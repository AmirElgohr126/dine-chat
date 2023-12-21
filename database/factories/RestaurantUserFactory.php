<?php

namespace Database\Factories;

use App\Models\RestaurantUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RestaurantUser>
 */
class RestaurantUserFactory extends Factory
{

    protected $model = RestaurantUser::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'user_name' => $this->faker->userName,
            'email' => $this->faker->unique()->safeEmail,
            'photo' => 'Dafaults/User/user.png',
            'phone' => $this->faker->unique()->phoneNumber,
            'password' => Hash::make('password'), // Hash the password
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'restaurant_id' => 1, // Replace with the actual restaurant_id
            'email_verified_at' => now(),
        ];
    }
}
