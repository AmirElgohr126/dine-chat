<?php

namespace Database\Seeders;

use App\Models\FoodImage;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FoodImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FoodImage::factory()->count(10)->create();
    }
}
