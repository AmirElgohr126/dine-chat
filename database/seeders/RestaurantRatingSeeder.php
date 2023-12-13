<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RestaurantRating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RestaurantRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RestaurantRating::factory()->count(20)->create();
    }
}
