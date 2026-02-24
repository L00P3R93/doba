<?php

namespace App\Listeners;

use App\Events\MediaUploaded;
use App\Models\Album;
use App\Models\Ep;
use App\Models\EpSong;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use App\Models\Single;
use App\Models\Song;

class UpdateMediaUrlListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MediaUploaded $event): void
    {
        $model = $event->model;
        $collectionName = $event->collectionName;
        $media = $event->media;

        // Log debugging information
        \Log::info('UpdateMediaUrlListener called', [
            'model_class' => get_class($model),
            'model_id' => $model->id ?? 'null',
            'collection_name' => $collectionName,
            'media_id' => $media->id,
        ]);

        // Check if model exists in database
        if (! $model->exists) {
            \Log::warning('Model does not exist in database yet, skipping URL update');

            return;
        }

        // Reload model to ensure we have latest data
        $model = $model->fresh();

        // Define mapping of model classes to their URL fields and collection names
        $urlConfig = [
            // Existing models
            Album::class => [
                'collections' => [
                    'covers' => 'cover',  // collection name => database field
                ],
            ],
            Ep::class => [
                'collections' => [
                    'covers' => 'cover',  // collection name => database field
                ],
            ],
            Podcast::class => [
                'collections' => [
                    'covers' => 'cover',  // collection name => database field
                ],
            ],
            Song::class => [
                'collections' => [
                    'songs' => 'url',     // collection name => database field
                ],
            ],
            EpSong::class => [
                'collections' => [
                    'songs' => 'url',     // collection name => database field
                ],
            ],
            PodcastEpisode::class => [
                'collections' => [
                    'episodes' => 'url',     // collection name => database field
                ],
            ],
            // New Single model with multiple collections
            Single::class => [
                'collections' => [
                    'singles' => 'url',    // Audio files -> url field
                    'covers' => 'cover',  // Images -> cover field
                ],
            ],
        ];

        // Check if model type is configured
        $modelClass = get_class($model);
        if (! isset($urlConfig[$modelClass])) {
            \Log::warning('Model class not configured in UpdateMediaUrlListener', ['model_class' => $modelClass]);

            return;
        }

        $config = $urlConfig[$modelClass];

        // Check if this collection is configured for the model
        if (! isset($config['collections'][$collectionName])) {
            \Log::warning('Collection not configured for model', [
                'model_class' => $modelClass,
                'collection_name' => $collectionName,
            ]);

            return;
        }

        // Get the database field to update
        $fieldToUpdate = $config['collections'][$collectionName];

        // Get the appropriate URL based on media type and collection
        try {
            $url = $this->getMediaUrl($media, $collectionName);
        } catch (\Exception $e) {
            \Log::error('Failed to get media URL', [
                'error' => $e->getMessage(),
                'media_id' => $media->id,
                'collection_name' => $collectionName,
            ]);

            return;
        }

        \Log::info('Updating model URL', [
            'model_class' => $modelClass,
            'model_id' => $model->id,
            'field_to_update' => $fieldToUpdate,
            'url' => $url,
        ]);

        // Update the model with the URL
        $model->{$fieldToUpdate} = $url;

        try {
            // Save without triggering events to avoid loops
            $model->saveQuietly();

            \Log::info('Model URL updated successfully', [
                'model_class' => $modelClass,
                'model_id' => $model->id,
                'field' => $fieldToUpdate,
                'new_value' => $model->{$fieldToUpdate},
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to save model URL update', [
                'error' => $e->getMessage(),
                'model_class' => $modelClass,
                'model_id' => $model->id,
                'field' => $fieldToUpdate,
            ]);
        }
    }

    /**
     * Get the appropriate URL for the media based on collection type
     */
    private function getMediaUrl($media, string $collectionName): string
    {
        // For cover images across any model
        if ($collectionName === 'covers') {
            // You can add image conversions here if needed
            // For example, if you have a 'thumbnail' conversion:
            // return $media->getUrl('thumbnail');

            return $media->getUrl();
        }

        // For audio files (singles or songs collections)
        if (in_array($collectionName, ['singles', 'songs'])) {
            return $media->getUrl();
        }

        // Default fallback
        return $media->getUrl();
    }
}
