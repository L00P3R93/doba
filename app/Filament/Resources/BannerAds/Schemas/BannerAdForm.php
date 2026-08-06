<?php

namespace App\Filament\Resources\BannerAds\Schemas;

use App\Enums\AdTargetLevel;
use App\Enums\BannerAdStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BannerAdForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Ad Content')->schema([
                TextInput::make('headline')
                    ->required()
                    ->maxLength(255),
                TextInput::make('cta_text')
                    ->label('Call to Action')
                    ->required()
                    ->maxLength(50),
                Section::make('Upload Banner')->schema([
                    SpatieMediaLibraryFileUpload::make('banner')
                        ->collection('banner')
                        ->image()
                        ->imageEditor()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(4096)
                        ->required()
                        ->columnSpanFull(),
                ])->columnSpanFull(),
            ])->columns(2)->columnSpanFull(),

            Section::make('Targeting')->schema([
                Select::make('target_level')
                    ->options(array_column(AdTargetLevel::cases(), 'value', 'value'))
                    ->formatStateUsing(fn ($state) => AdTargetLevel::from($state)?->getLabel())
                    ->required()
                    ->reactive(),
                Select::make('target_county_id')
                    ->relationship('targetCounty', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('target_sub_county_id')
                    ->relationship('targetSubCounty', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('target_ward_id')
                    ->relationship('targetWard', 'name')
                    ->searchable()
                    ->preload(),
            ])->columns(2)->columnSpanFull(),

            Section::make('Pricing')->schema([
                TextInput::make('base_price_per_impression')
                    ->label('Base Price per Impression')
                    ->numeric()
                    ->step(0.01)
                    ->suffix('KES')
                    ->required(),
                TextInput::make('price_per_impression')
                    ->label('Final Price per Impression')
                    ->numeric()
                    ->step(0.01)
                    ->suffix('KES')
                    ->disabled()
                    ->dehydrated(false),
                TextInput::make('budget')
                    ->numeric()
                    ->step(0.01)
                    ->suffix('KES')
                    ->required(),
                TextInput::make('max_impressions')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false),
            ])->columns(2)->columnSpanFull(),

            Section::make('Performance')->schema([
                TextInput::make('impressions')
                    ->numeric()
                    ->default(0),
                TextInput::make('clicks')
                    ->numeric()
                    ->default(0),
            ])->columns(2)->columnSpanFull(),

            Section::make('Status & Schedule')->schema([
                Select::make('status')
                    ->options(array_column(BannerAdStatus::cases(), 'value', 'value'))
                    ->formatStateUsing(fn ($state) => BannerAdStatus::from($state)?->getLabel())
                    ->required()
                    ->default(BannerAdStatus::Pending->value),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(false),
                TextInput::make('priority')
                    ->numeric()
                    ->default(1),
                DatePicker::make('starts_at')
                    ->default(now()),
                DatePicker::make('ends_at')
                    ->default(now()->addDays(30)),
            ])->columns(2)->columnSpanFull(),
        ]);
    }
}
