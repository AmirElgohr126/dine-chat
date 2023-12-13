<?php

namespace Database\Factories;


use App\Models\AboutApplication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AboutApplicationFactory extends Factory
{

    protected $model = AboutApplication::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'orivacy_policy' => $this->faker->paragraph,
            'about_us' => $this->faker->paragraph,
            'terms_conditions' => $this->faker->paragraph,
        ];
    }
}
