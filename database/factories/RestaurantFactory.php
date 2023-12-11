<?php

namespace Database\Factories;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Restaurant>
 */
class RestaurantFactory extends Factory
{

    protected $model = Restaurant::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number_of_floors' => $this->faker->randomNumber(2),
            'number_of_departments' => $this->faker->randomNumber(2),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'phone' => $this->faker->phoneNumber,
            'images' => $this->faker->imageUrl,
            'en' => [
                'name' => $this->faker->words(2, true), // English translation
            ],
            'ar' => [
                'name' => $this->faker->words(2, true), // English translation
            ]
        ];
    }
}
