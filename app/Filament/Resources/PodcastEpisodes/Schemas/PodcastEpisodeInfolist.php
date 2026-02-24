<?php

namespace App\Filament\Resources\PodcastEpisodes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PodcastEpisodeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('podcast.title')
                    ->label('Podcast'),
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('url')
                    ->placeholder('-'),
                TextEntry::make('duration')
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
