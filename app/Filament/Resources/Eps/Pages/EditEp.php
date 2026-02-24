<?php

namespace App\Filament\Resources\Eps\Pages;

use App\Filament\Resources\Eps\EpResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEp extends EditRecord
{
    protected static string $resource = EpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
