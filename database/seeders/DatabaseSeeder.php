<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserFollowerSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this
            ->call(UserSeeder::class)
            ->call(ContactSeeder::class)
            ->call(UserFollowerSeeder::class)
            ->call(RestaurantSeeder::class)
            ->call(TableSeeder::class)
            ->call(ChairSeeder::class)
            ->call(FoodSeeder::class);
    }
}
