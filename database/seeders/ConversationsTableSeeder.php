<?php

namespace Database\Seeders;

use App\Models\Conversation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ConversationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Conversation::factory(50)->create();
    }
}
