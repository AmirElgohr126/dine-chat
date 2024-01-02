<?php

namespace App\Console\Commands;

use App\Models\Conversation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteOldChats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-old-chats';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'delete old chat that deleted at bigger than now';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $oldChats = Conversation::where('deleted_at', '<', now()->subDays(10))->onlyTrashed()->get();
        foreach ($oldChats as $chat) {
            $nameDriverDeleted = "Chats/chat" . $chat->id;
            if (Storage::disk('public')->exists($nameDriverDeleted)) {
                try{
                    Storage::disk('public')->deleteDirectory($nameDriverDeleted);
                    $chat->forceDelete();
                } catch (\Exception $e) {
                    Log::error("Failed to delete directory", ['directory' => $nameDriverDeleted, 'error' => $e->getMessage()]);
                }
            } else {
                $chat->forceDelete();
            }
        }
    }
}

