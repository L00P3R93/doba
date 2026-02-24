<?php

namespace App\Providers;

use App\Events\MediaUploaded;
use App\Listeners\UpdateMediaUrlListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MediaUploaded::class => [
            UpdateMediaUrlListener::class,
        ],
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        parent::boot();
    }
}
