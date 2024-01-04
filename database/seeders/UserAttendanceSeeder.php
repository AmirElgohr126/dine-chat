<?php

namespace Database\Seeders;

use App\Models\UserAttendance;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserAttendance::factory(20)->create();
    }
}
