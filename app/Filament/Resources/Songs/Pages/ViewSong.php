<?php

namespace App\Filament\Resources\Songs\Pages;

use App\Filament\Resources\Songs\SongResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSong extends ViewRecord
{
    protected static string $resource = SongResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
