<?php

namespace App\Filament\Resources\EpSongs\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
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
                Section::make()->schema([
                    Select::make('ep_id')
                        ->label('EP')
                        ->relationship(
                            name: 'ep',
                            titleAttribute: 'title',
                            modifyQueryUsing: fn ($query) => auth()->user()->isAdmin() ? $query->latest()->limit(10) : $query->where('user_id', auth()->user()->id)->latest()->limit(10))
                        ->native(false)
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Section::make('EP Details')->schema([
                                TextInput::make('title')
                                    ->required(),
                                TextInput::make('year')
                                    ->required()
                                    ->default('2026'),
                                Section::make('Upload Cover')->schema([
                                    SpatieMediaLibraryFileUpload::make('cover')
                                        ->collection('covers')
                                        ->preserveFilenames()
                                        ->downloadable()
                                        ->openable()
                                        ->columnSpanFull(),
                                ])->columnSpanFull(),
                            ])->columns(2)->columnSpanFull(),
                            Section::make('EP Description')->schema([
                                MarkdownEditor::make('description')
                                    ->columnSpanFull(),
                            ])->columnSpanFull()->collapsed(),
                            Section::make('Update EP')->schema([
                                Toggle::make('is_active')
                                    ->default(true)
                                    ->required(),
                                Toggle::make('hot_or_cold')
                                    ->default(true)
                                    ->required(),
                                Select::make('user_id')
                                    ->label('Artist')
                                    ->relationship(name: 'user', titleAttribute: 'name', modifyQueryUsing: fn ($query) => $query->latest()->limit(10))
                                    ->native(false)
                                    ->preload()
                                    ->searchable()
                                    ->required(),
                            ])->columns(2)->visible(fn () => auth()->user()->isAdmin())->columnSpanFull(),
                        ])
                        ->createOptionAction(function (Action $action) {
                            $action
                                ->modalHeading('Create New EP')
                                ->modalDescription('Creat a New EP for this song upload')
                                ->modalSubmitActionLabel('Create EP');
                        })
                        ->required(),
                    TextInput::make('title')
                        ->required(),
                    TextInput::make('duration'),
                    TextInput::make('copyright_holder')
                        ->required(),
                    TextInput::make('copyright_year')
                        ->required(),
                    TextInput::make('production_year')
                        ->required(),
                    TextInput::make('record_label')
                        ->required(),
                    Section::make('Upload EP Song')->schema([
                        SpatieMediaLibraryFileUpload::make('song')
                            ->preserveFilenames()
                            ->downloadable()
                            ->openable()
                            ->collection('songs')
                            ->acceptedFileTypes(['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp3', 'audio/x-wav', 'audio/x-mpeg-3'])
                            ->maxSize(50000), // 50MB
                    ])->columnSpanFull(),

                ])->columns(2)->columnSpanFull(),
                Section::make('Update EP Song')->schema([
                    Toggle::make('is_active')
                        ->default(true)
                        ->required(),
                    Toggle::make('hot_or_cold')
                        ->default(true)
                        ->required(),
                ])->columns(2)->visible(fn () => auth()->user()->isAdmin())->columnSpanFull(),
            ]);
    }
}
