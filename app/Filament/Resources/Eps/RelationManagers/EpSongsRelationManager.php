<?php

namespace App\Filament\Resources\Eps\RelationManagers;

use App\Filament\Resources\EpSongs\EpSongResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class EpSongsRelationManager extends RelationManager
{
    protected static string $relationship = 'ep_songs';

    protected static ?string $relatedResource = EpSongResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
