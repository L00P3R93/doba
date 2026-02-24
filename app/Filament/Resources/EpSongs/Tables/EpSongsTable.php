<?php

namespace App\Filament\Resources\EpSongs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EpSongsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                auth()->user()->isAdmin() ? $query->with('ep.user') : $query->whereHas('ep.user', function (Builder $query) {
                    $query->where('id', auth()->user()->id);
                });
            })
            ->columns([
                TextColumn::make('ep.title')
                    ->searchable(),
                TextColumn::make('ep.user.name')
                    ->label('Artist')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('duration')
                    ->searchable(),
                TextColumn::make('copyright_holder')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('copyright_year')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('production_year')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('record_label')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->boolean(),
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
