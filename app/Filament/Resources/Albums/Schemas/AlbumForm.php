<?php

namespace App\Filament\Resources\Albums\Schemas;

use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AlbumForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
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
            Section::make('Album Description')->schema([
                MarkdownEditor::make('description')
                    ->columnSpanFull(),
            ])->columnSpanFull()->collapsed(),
            Section::make('Update Album')->schema([
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
        ]);
    }
}
