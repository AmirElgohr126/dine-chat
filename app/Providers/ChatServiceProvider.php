<?php

namespace App\Providers;

use App\Service\ChatServices\ChatService;
use App\Service\ChatServices\ChatServiceInterface;
use Illuminate\Support\ServiceProvider;

class ChatServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ChatServiceInterface::class,ChatService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
