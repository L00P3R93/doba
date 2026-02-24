<?php

namespace App\Filament\Resources\Podcasts\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PodcastForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('host'),
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Section::make('Upload Cover')->schema([
                    SpatieMediaLibraryFileUpload::make('covers')
                        ->preserveFilenames()
                        ->downloadable()
                        ->openable()
                        ->collection('covers')
                        ->image()
                        ->imageEditor(),
                ]),
            ]);
    }
}
