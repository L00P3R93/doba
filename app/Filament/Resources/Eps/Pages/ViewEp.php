<?php

namespace App\Filament\Resources\Eps\Pages;

use App\Filament\Resources\Eps\EpResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEp extends ViewRecord
{
    protected static string $resource = EpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
