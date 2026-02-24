<?php

namespace App\Filament\Resources\EpSongs\Pages;

use App\Filament\Resources\EpSongs\EpSongResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEpSongs extends ListRecords
{
    protected static string $resource = EpSongResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
