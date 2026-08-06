<?php

namespace App\Filament\Resources\BannerAds\Tables;

use App\Enums\AdTargetLevel;
use App\Enums\BannerAdStatus;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BannerAdsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('banner')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png')),
                TextColumn::make('headline')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('target_level')
                    ->formatStateUsing(fn ($state) => AdTargetLevel::from($state)?->getLabel())
                    ->badge()
                    ->color(fn ($state) => AdTargetLevel::from($state)?->getColor()),
                TextColumn::make('status')
                    ->formatStateUsing(fn ($state) => BannerAdStatus::from($state)?->getLabel())
                    ->badge()
                    ->color(fn ($state) => BannerAdStatus::from($state)?->getColor()),
                TextColumn::make('price_per_impression')
                    ->money('KES')
                    ->sortable(),
                TextColumn::make('impressions')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('clicks')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('budget')
                    ->money('KES')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Advertiser')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('target_level')
                    ->options(array_column(AdTargetLevel::cases(), 'value', 'value'))
                    ->formatStateUsing(fn ($state) => AdTargetLevel::from($state)?->getLabel()),
                SelectFilter::make('status')
                    ->options(array_column(BannerAdStatus::cases(), 'value', 'value'))
                    ->formatStateUsing(fn ($state) => BannerAdStatus::from($state)?->getLabel()),
                SelectFilter::make('is_active')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === BannerAdStatus::Pending)
                    ->action(function ($record) {
                        $record->update([
                            'status' => BannerAdStatus::Active->value,
                            'is_active' => true,
                        ]);
                    }),
                Action::make('pause')
                    ->label('Pause')
                    ->icon('heroicon-o-pause-circle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === BannerAdStatus::Active && $record->is_active)
                    ->action(function ($record) {
                        $record->update(['is_active' => false]);
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === BannerAdStatus::Pending)
                    ->action(function ($record) {
                        $record->update(['status' => BannerAdStatus::Rejected->value]);
                    }),
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
