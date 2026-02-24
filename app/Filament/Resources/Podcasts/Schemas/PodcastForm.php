<?php

namespace App\Filament\Resources\Podcasts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PodcastForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
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
                Section::make('Update Podcasr')->schema([
                    Select::make('user_id')
                        ->label('Uploader')
                        ->relationship(name: 'user', titleAttribute: 'name', modifyQueryUsing: fn ($query) => $query->latest()->limit(10))
                        ->native(false)
                        ->preload()
                        ->searchable()
                        ->required(),
                ])->columns(2)->visible(fn () => auth()->user()->isAdmin())->columnSpanFull(),
            ]);
    }
}
