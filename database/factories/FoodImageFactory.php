<?php

namespace Database\Factories;

use App\Models\Food;
use App\Models\FoodImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FoodImage>
 */
class FoodImageFactory extends Factory
{
        protected $model = FoodImage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'food_id' => function () {
                // You can customize how to get the 'food_id' here, e.g., using the Food model to get a random food_id.
                return Food::factory()->create()->id;
            },
            'image' => $this->faker->imageUrl(), // You can customize how to generate images.
        ];
    }
}
