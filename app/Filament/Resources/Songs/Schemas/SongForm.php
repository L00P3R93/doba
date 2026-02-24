<?php

namespace App\Filament\Resources\Songs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SongForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    Select::make('album_id')
                        ->label('Album')
                        ->relationship(
                            name: 'album',
                            titleAttribute: 'title',
                            modifyQueryUsing: fn ($query) => auth()->user()->isAdmin() ? $query->latest()->limit(10) : $query->where('user_id', auth()->user()->id)->latest()->limit(10))
                        ->native(false)
                        ->searchable()
                        ->preload()
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
                    Section::make('Upload Song')->schema([
                        SpatieMediaLibraryFileUpload::make('song')
                            ->preserveFilenames()
                            ->downloadable()
                            ->openable()
                            ->collection('songs')
                            ->acceptedFileTypes(['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp3', 'audio/x-wav', 'audio/x-mpeg-3'])
                            ->maxSize(50000), // 50MB
                    ])->columnSpanFull(),

                ])->columns(2)->columnSpanFull(),
                Section::make('Update Song')->schema([
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
