<?php

namespace App\Filament\Resources\Eps\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EpForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('year')
                    ->required()
                    ->default('2026'),
                Textarea::make('description')
                    ->columnSpanFull(),
                Section::make('Upload Cover')->schema([
                    SpatieMediaLibraryFileUpload::make('covers')
                        ->preserveFilenames()
                        ->downloadable()
                        ->openable()
                        ->collection('covers')
                        ->image()
                        ->imageEditor(),
                ]),
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
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('hot_or_cold')
                    ->required(),
            ]);
    }
}
