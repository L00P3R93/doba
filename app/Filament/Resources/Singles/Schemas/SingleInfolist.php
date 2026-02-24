<?php

namespace App\Filament\Resources\Singles\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SingleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('cover')
                    ->placeholder('-'),
                TextEntry::make('url')
                    ->placeholder('-'),
                TextEntry::make('year')
                    ->placeholder('-'),
                TextEntry::make('duration')
                    ->placeholder('-'),
                TextEntry::make('streams')
                    ->numeric(),
                TextEntry::make('copyright_holder'),
                TextEntry::make('copyright_year'),
                TextEntry::make('production_year'),
                TextEntry::make('record_label'),
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
                TextEntry::make('jorna'),
                IconEntry::make('is_active')
                    ->boolean(),
                IconEntry::make('hot_or_cold')
                    ->boolean(),
                TextEntry::make('video_url')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
