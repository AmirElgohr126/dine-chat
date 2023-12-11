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
            'chair_number' => $this->faker->unique()->randomNumber(3),
            'table_id' =>  $tableId,
            'restaurant_id' => $restaurantId
        ];
    }
}
