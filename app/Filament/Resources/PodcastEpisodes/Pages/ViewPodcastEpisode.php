<?php

namespace App\Filament\Resources\PodcastEpisodes\Pages;

use App\Filament\Resources\PodcastEpisodes\PodcastEpisodeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPodcastEpisode extends ViewRecord
{
    protected static string $resource = PodcastEpisodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
