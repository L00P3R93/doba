<?php

namespace App\Filament\Resources\Albums\RelationManagers;

use App\Filament\Resources\Songs\SongResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class SongsRelationManager extends RelationManager
{
    protected static string $relationship = 'songs';

    protected static ?string $relatedResource = SongResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
