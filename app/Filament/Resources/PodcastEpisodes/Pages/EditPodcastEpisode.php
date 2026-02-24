<?php

namespace App\Filament\Resources\PodcastEpisodes\Pages;

use App\Filament\Resources\PodcastEpisodes\PodcastEpisodeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPodcastEpisode extends EditRecord
{
    protected static string $resource = PodcastEpisodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
