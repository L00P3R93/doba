<?php

namespace App\Filament\Resources\EpSongs;

use App\Filament\Resources\EpSongs\Pages\CreateEpSong;
use App\Filament\Resources\EpSongs\Pages\EditEpSong;
use App\Filament\Resources\EpSongs\Pages\ListEpSongs;
use App\Filament\Resources\EpSongs\Pages\ViewEpSong;
use App\Filament\Resources\EpSongs\Schemas\EpSongForm;
use App\Filament\Resources\EpSongs\Schemas\EpSongInfolist;
use App\Filament\Resources\EpSongs\Tables\EpSongsTable;
use App\Models\EpSong;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EpSongResource extends Resource
{
    protected static ?string $model = EpSong::class;

    protected static string|BackedEnum|null $navigationIcon = 'iconoir-music-note-solid';

    protected static string | UnitEnum | null $navigationGroup = 'EPs & Songs';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return EpSongForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EpSongInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EpSongsTable::configure($table);
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
            'index' => ListEpSongs::route('/'),
            'create' => CreateEpSong::route('/create'),
            'view' => ViewEpSong::route('/{record}'),
            'edit' => EditEpSong::route('/{record}/edit'),
        ];
    }
}
