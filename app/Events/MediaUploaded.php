<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaUploaded
{
    use Dispatchable, SerializesModels;

    public Media $media;

    public mixed $model;

    public string $collectionName;

    /**
     * Create a new event instance.
     */
    public function __construct(Media $media)
    {
        $this->media = $media;
        $this->model = $media->model;
        $this->collectionName = $media->collection_name;
    }
}
