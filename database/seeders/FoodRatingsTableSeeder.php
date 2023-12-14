<?php

namespace Database\Seeders;

use App\Models\FoodRating;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FoodRatingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FoodRating::factory(50)->create();
    }
}
