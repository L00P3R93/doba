<?php

namespace App\Filament\Resources\Podcasts\RelationManagers;

use App\Filament\Resources\PodcastEpisodes\PodcastEpisodeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class EpisodesRelationManager extends RelationManager
{
    protected static string $relationship = 'episodes';

    protected static ?string $relatedResource = PodcastEpisodeResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
