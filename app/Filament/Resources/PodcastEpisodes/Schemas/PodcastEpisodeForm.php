<?php

namespace App\Filament\Resources\PodcastEpisodes\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
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
                        ->createOptionForm([
                            Section::make('Podcast Details')->schema([
                                TextInput::make('host'),
                                TextInput::make('title')
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
                            ])->columnSpanFull(),
                            Section::make('Update Podcast')->schema([
                                Select::make('user_id')
                                    ->label('Uploader')
                                    ->relationship(name: 'user', titleAttribute: 'name', modifyQueryUsing: fn ($query) => $query->latest()->limit(10))
                                    ->native(false)
                                    ->preload()
                                    ->searchable()
                                    ->required(),
                            ])->columns(2)->visible(fn () => auth()->user()->isAdmin())->columnSpanFull(),
                        ])
                        ->createOptionAction(function (Action $action) {
                            $action
                                ->modalHeading('Create New Podcast')
                                ->modalDescription('Creat a New Podcats for this Episode upload')
                                ->modalSubmitActionLabel('Create Podcast');
                        })
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
