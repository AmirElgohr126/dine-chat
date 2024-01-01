<?php

namespace Database\Factories;

use App\Models\Chair;
use App\Models\Table;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chair>
 */
class ChairFactory extends Factory
{

    protected $model = Chair::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $tableId = Table::inRandomOrder()->first()->id;
        $restaurantId = Restaurant::inRandomOrder()->first()->id;
        return [
            'restaurant_id' => Restaurant::factory(), // Assuming you have a Restaurant Factory
            'x' => $this->faker->randomFloat(8, 0, 100), // Adjust the range as needed
            'y' => $this->faker->randomFloat(8, 0, 100), // Adjust the range as needed
            'img' => $this->faker->imageUrl(),
            'key' => $this->faker->unique()->word,
            'nfc_number' => $this->faker->optional()->randomNumber(),
            'name' => $this->faker->word,
        ];
    }
}
