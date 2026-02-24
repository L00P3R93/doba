<?php

namespace App\Filament\Resources\Singles\Pages;

use App\Filament\Resources\Singles\SingleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSingle extends ViewRecord
{
    protected static string $resource = SingleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
