<?php

namespace App\Providers;

use App\Events\MediaUploaded;
use Illuminate\Support\ServiceProvider;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;

class MediaEventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app['events']->listen(
            MediaHasBeenAddedEvent::class,
            function ($event) {
                \Log::info('MediaHasBeenAddedEvent fired', [
                    'media_id' => $event->media->id,
                    'model_class' => get_class($event->media->model),
                    'model_id' => $event->media->model->id,
                    'collection_name' => $event->media->collection_name,
                ]);
                
                MediaUploaded::dispatch($event->media);
            }
        );
    }
}
