<?php

namespace Database\Factories;

use App\Models\RestaurantRating;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RestaurantRating>
 */
class RestaurantRatingFactory extends Factory
{
    protected $model = RestaurantRating::class;

    public function definition()
    {
        return [
            'restaurant_id' => 11,
            'user_id' => function () {
                // Assuming you have User model factory
                return \App\Models\User::factory()->create()->id;
            },
            'rating' => $this->faker->numberBetween(1, 5),
        ];
    }
}
