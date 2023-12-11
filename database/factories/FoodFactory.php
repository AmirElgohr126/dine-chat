<?php

namespace Database\Factories;

use App\Models\Food;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Food>
 */
class FoodFactory extends Factory
{

    protected $model = Food::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $restaurantId = Restaurant::inRandomOrder()->first()->id;
        return [
            'restaurant_id' => $restaurantId,
            'price' => $this->faker->randomFloat(2, 1, 100),
            'en' => [
                'name' => $this->faker->words(2, true), // English translation
            ],
            'ar' => [
                'name' => $this->faker->words(2, true), // arabic translation
            ]
        ];
    }
}
