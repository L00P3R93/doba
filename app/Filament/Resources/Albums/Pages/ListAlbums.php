<?php

namespace App\Filament\Resources\Albums\Pages;

use App\Filament\Resources\Albums\AlbumResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListAlbums extends ListRecords
{
    protected static string $resource = AlbumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return AlbumResource::getWidgets();
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'hot' => Tab::make('Hot Albums')->query(fn ($query) => $query->where('hot_or_cold', true)),
            'active' => Tab::make('Active')->query(fn ($query) => $query->where('is_active', true)),
            'in_active' => Tab::make('In Active')->query(fn ($query) => $query->where('is_active', false)),
        ];
    }
}
