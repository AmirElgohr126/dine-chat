<?php

namespace Database\Seeders;

use App\Models\Chair;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChairSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Chair::factory(10)->create();
    }
}
