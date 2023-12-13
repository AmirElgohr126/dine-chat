<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Chair;
use App\Models\Table;
use App\Models\Restaurant;
use App\Models\UserAttendance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAttendance>
 */
class UserAttendanceFactory extends Factory
{

    protected $model = UserAttendance::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'restaurant_id' => 11,
            'chair_id' => function () {
                return Chair::factory()->create()->id;
            },
            'table_id' => function () {
                return Table::factory()->create()->id;
            },
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
