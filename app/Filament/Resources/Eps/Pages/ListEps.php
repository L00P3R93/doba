<?php

namespace App\Filament\Resources\Eps\Pages;

use App\Filament\Resources\Eps\EpResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEps extends ListRecords
{
    protected static string $resource = EpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
