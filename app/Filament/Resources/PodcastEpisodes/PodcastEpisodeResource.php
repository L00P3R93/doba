<?php

namespace App\Filament\Resources\PodcastEpisodes;

use App\Filament\Resources\PodcastEpisodes\Pages\CreatePodcastEpisode;
use App\Filament\Resources\PodcastEpisodes\Pages\EditPodcastEpisode;
use App\Filament\Resources\PodcastEpisodes\Pages\ListPodcastEpisodes;
use App\Filament\Resources\PodcastEpisodes\Pages\ViewPodcastEpisode;
use App\Filament\Resources\PodcastEpisodes\Schemas\PodcastEpisodeForm;
use App\Filament\Resources\PodcastEpisodes\Schemas\PodcastEpisodeInfolist;
use App\Filament\Resources\PodcastEpisodes\Tables\PodcastEpisodesTable;
use App\Models\PodcastEpisode;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PodcastEpisodeResource extends Resource
{
    protected static ?string $model = PodcastEpisode::class;

    protected static string|BackedEnum|null $navigationIcon = 'hugeicons-group-01';
    protected static string | UnitEnum | null $navigationGroup = 'Podcasts';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return PodcastEpisodeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PodcastEpisodeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PodcastEpisodesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPodcastEpisodes::route('/'),
            'create' => CreatePodcastEpisode::route('/create'),
            'view' => ViewPodcastEpisode::route('/{record}'),
            'edit' => EditPodcastEpisode::route('/{record}/edit'),
        ];
    }
}
