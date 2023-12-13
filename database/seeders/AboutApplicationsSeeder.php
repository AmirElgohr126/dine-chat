<?php

namespace Database\Seeders;

use App\Models\AboutApplication;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AboutApplicationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AboutApplication::factory()->count(1)->create();
    }
}
