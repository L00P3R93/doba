<?php

namespace App\Filament\Resources\Eps\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EpInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('year'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('cover')
                    ->placeholder('-'),
                TextEntry::make('likes')
                    ->numeric(),
                TextEntry::make('views')
                    ->numeric(),
                TextEntry::make('comments')
                    ->numeric(),
                TextEntry::make('shares')
                    ->numeric(),
                TextEntry::make('downloads')
                    ->numeric(),
                TextEntry::make('favorites')
                    ->numeric(),
                IconEntry::make('is_active')
                    ->boolean(),
                IconEntry::make('hot_or_cold')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
