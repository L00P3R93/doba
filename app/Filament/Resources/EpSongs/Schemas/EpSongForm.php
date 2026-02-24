<?php

namespace App\Filament\Resources\EpSongs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EpSongForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('ep_id')
                    ->relationship('ep', 'title')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Section::make('Upload Song')->schema([
                    SpatieMediaLibraryFileUpload::make('songs')
                        ->preserveFilenames()
                        ->downloadable()
                        ->openable()
                        ->collection('songs')
                        ->acceptedFileTypes(['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp3', 'audio/x-wav', 'audio/x-mpeg-3'])
                        ->maxSize(50000), // 50MB
                ]),
                TextInput::make('duration'),
                TextInput::make('streams')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('copyright_holder')
                    ->required(),
                TextInput::make('copyright_year')
                    ->required(),
                TextInput::make('production_year')
                    ->required(),
                TextInput::make('record_label')
                    ->required(),
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
                TextInput::make('jorna')
                    ->required()
                    ->default('general'),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('hot_or_cold')
                    ->required(),
                TextInput::make('video_url')
                    ->url(),
            ]);
    }
}
