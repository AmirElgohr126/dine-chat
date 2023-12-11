<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\ContactsListRepository\ContactsListInterface;
use App\Repository\ContactsListRepository\ContactsListRepository;

class ContactListServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ContactsListInterface::class,ContactsListRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
