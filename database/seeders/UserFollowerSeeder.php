<?php

namespace Database\Seeders;

use App\Models\UserFollower;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserFollowerSeeder extends Seeder
{

    public function run(): void
    {
        UserFollower::factory(1)->create();
    }
}
