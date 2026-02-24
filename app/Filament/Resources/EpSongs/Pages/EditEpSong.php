<?php

namespace App\Filament\Resources\EpSongs\Pages;

use App\Filament\Resources\EpSongs\EpSongResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEpSong extends EditRecord
{
    protected static string $resource = EpSongResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
