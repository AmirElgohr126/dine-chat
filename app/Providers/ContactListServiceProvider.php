<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\V1\ContactsListRepository\ContactsListInterface;
use App\Repository\V1\ContactsListRepository\ContactsListRepository;


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
