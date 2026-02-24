<?php

namespace App\Filament\Resources\PodcastEpisodes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PodcastEpisodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('podcast_id')
                    ->relationship('podcast', 'title')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Section::make('Upload Episode')->schema([
                    SpatieMediaLibraryFileUpload::make('episodes')
                        ->preserveFilenames()
                        ->downloadable()
                        ->openable()
                        ->collection('episodes')
                        ->acceptedFileTypes(['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp3', 'audio/x-wav', 'audio/x-mpeg-3'])
                        ->maxSize(50000), // 50MB
                ]),
                TextInput::make('duration'),
                TextInput::make('likes')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('views')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('comments')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('shares')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('downloads')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('favorites')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('video_url')
                    ->url(),
            ]);
    }
}
