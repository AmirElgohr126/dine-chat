<?php

namespace Database\Seeders;

use App\Models\RestaurantUser;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RestaurantUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RestaurantUser::factory()->count(1)->create();
    }
}
