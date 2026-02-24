<?php

namespace App\Filament\Resources\EpSongs\Pages;

use App\Filament\Resources\EpSongs\EpSongResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEpSong extends ViewRecord
{
    protected static string $resource = EpSongResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
