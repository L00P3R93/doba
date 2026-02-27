<?php

namespace App\Filament\Widgets\Admin;

use App\Models\Song;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopSongsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Song::with('album')
                    ->orderBy('streams', 'desc')
                    ->orderBy('likes', 'desc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('album.title')
                    ->label('Album')
                    ->searchable()
                    ->sortable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('streams')
                    ->label('Streams')
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string => number_format($state))
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('likes')
                    ->label('Likes')
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string => number_format($state))
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('views')
                    ->label('Views')
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string => number_format($state))
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('hot_or_cold')
                    ->label('Trending')
                    ->boolean()
                    ->trueColor('danger')
                    ->falseColor('gray')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('jorna')
                    ->label('Category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'general' => 'gray',
                        'gospel' => 'success',
                        'secular' => 'primary',
                        default => 'warning',
                    }),
            ])
            ->defaultSort('streams', 'desc')
            ->heading('Top Performing Songs')
            ->description('Most streamed and liked songs');
    }
}
