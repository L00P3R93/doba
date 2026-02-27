<?php

namespace App\Filament\Widgets\Admin;

use App\Models\Album;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopAlbumsWidget extends BaseWidget
{
    protected static ?int $sort = 8;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Album::with('user')
                    ->orderBy('likes', 'desc')
                    ->orderBy('views', 'desc')
                    ->limit(8)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(25),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Artist')
                    ->searchable()
                    ->sortable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('year')
                    ->label('Year')
                    ->sortable()
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

                Tables\Columns\TextColumn::make('downloads')
                    ->label('Downloads')
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string => number_format($state))
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('hot_or_cold')
                    ->label('Trending')
                    ->boolean()
                    ->trueColor('danger')
                    ->falseColor('gray')
                    ->alignCenter(),
            ])
            ->defaultSort('likes', 'desc')
            ->heading('Top Performing Albums')
            ->description('Most liked and viewed albums');
    }
}
