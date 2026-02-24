<?php

namespace App\Filament\Resources\PodcastEpisodes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PodcastEpisodesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('podcast.title')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('url')
                    ->searchable(),
                TextColumn::make('duration')
                    ->searchable(),
                TextColumn::make('likes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('views')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('comments')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('shares')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('downloads')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('favorites')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('video_url')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
