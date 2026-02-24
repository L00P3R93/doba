<?php

namespace App\Filament\Resources\Singles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SingleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    TextInput::make('title')
                        ->required(),
                    TextInput::make('year'),
                    TextInput::make('duration'),
                    TextInput::make('copyright_holder')
                        ->required(),
                    TextInput::make('copyright_year')
                        ->required(),
                    TextInput::make('production_year')
                        ->required(),
                    TextInput::make('record_label')
                        ->required(),
                    Section::make('Upload Cover')->schema([
                        SpatieMediaLibraryFileUpload::make('covers')
                            ->preserveFilenames()
                            ->downloadable()
                            ->openable()
                            ->collection('covers')
                            ->image()
                            ->imageEditor(),
                    ])->columnSpanFull(),
                    Section::make('Upload Audio')->schema([
                        SpatieMediaLibraryFileUpload::make('singles')
                            ->preserveFilenames()
                            ->downloadable()
                            ->openable()
                            ->collection('singles')
                            ->acceptedFileTypes(['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp3', 'audio/x-wav', 'audio/x-mpeg-3'])
                            ->maxSize(50000), // 50MB
                    ])->columnSpanFull(),
                ])->columns(2)->columnSpanFull(),
                Section::make()->schema([
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
                ])->visible(fn () => auth()->user()->isAdmin())->columns(2)->columnSpanFull(),
            ]);
    }
}
