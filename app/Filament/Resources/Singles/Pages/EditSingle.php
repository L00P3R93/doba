<?php

namespace App\Filament\Resources\Singles\Pages;

use App\Filament\Resources\Singles\SingleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSingle extends EditRecord
{
    protected static string $resource = SingleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
