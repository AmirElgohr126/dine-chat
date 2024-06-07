<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

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
             ->call(FoodSeeder::class)
             ->call(FoodRatingsTableSeeder::class)
             ->call(AboutApplicationsSeeder::class)
             ->call(UserAttendanceSeeder::class)
             ->call(RestaurantRatingSeeder::class)
             ->call(FoodImagesTableSeeder::class)
             ->call(ConversationSeeder::class)
             ->call(MessagesTableSeeder::class)
             ->call(RestaurantUserSeeder::class);

    }
}
