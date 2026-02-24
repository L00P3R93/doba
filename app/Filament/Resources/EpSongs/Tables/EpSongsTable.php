<?php

namespace App\Filament\Resources\EpSongs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EpSongsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ep.title')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('url')
                    ->searchable(),
                TextColumn::make('duration')
                    ->searchable(),
                TextColumn::make('streams')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('copyright_holder')
                    ->searchable(),
                TextColumn::make('copyright_year'),
                TextColumn::make('production_year'),
                TextColumn::make('record_label')
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
                TextColumn::make('jorna')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean(),
                IconColumn::make('hot_or_cold')
                    ->boolean(),
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
