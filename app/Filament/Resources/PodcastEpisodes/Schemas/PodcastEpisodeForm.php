<?php

namespace App\Filament\Resources\PodcastEpisodes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PodcastEpisodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    Select::make('podcast_id')
                        ->label('Podcast')
                        ->relationship(
                            name: 'podcast',
                            titleAttribute: 'title',
                            modifyQueryUsing: fn ($query) => auth()->user()->isAdmin() ? $query->latest()->limit(10) : $query->where('user_id', auth()->user()->id)->latest()->limit(10))
                        ->native(false)
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('title')
                        ->required(),
                    TextInput::make('duration'),
                    Section::make('Upload Episode')->schema([
                        SpatieMediaLibraryFileUpload::make('episodes')
                            ->preserveFilenames()
                            ->downloadable()
                            ->openable()
                            ->collection('episodes')
                            ->acceptedFileTypes(['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp3', 'audio/x-wav', 'audio/x-mpeg-3'])
                            ->maxSize(50000), // 50MB
                    ])->columnSpanFull(),
                ])->columns(2)->columnSpanFull(),
            ]);
    }
}
