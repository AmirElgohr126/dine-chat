<?php

namespace Database\Factories;

use App\Models\Table;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Table>
 */
class TableFactory extends Factory
{

    protected $model = Table::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $restaurantId = Restaurant::inRandomOrder()->first()->id;
        return [
            'restaurant_id' => Restaurant::factory(),
            'x' => $this->faker->randomFloat(8, 0, 100),
            'y' => $this->faker->randomFloat(8, 0, 100),
            'img' => $this->faker->imageUrl(),
            'key' => $this->faker->unique()->word,
            'name' => $this->faker->word,
        ];
    }
}
