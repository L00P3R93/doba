<?php

namespace App\Filament\Resources\Songs;

use App\Filament\Resources\Songs\Pages\CreateSong;
use App\Filament\Resources\Songs\Pages\EditSong;
use App\Filament\Resources\Songs\Pages\ListSongs;
use App\Filament\Resources\Songs\Pages\ViewSong;
use App\Filament\Resources\Songs\Schemas\SongForm;
use App\Filament\Resources\Songs\Schemas\SongInfolist;
use App\Filament\Resources\Songs\Tables\SongsTable;
use App\Models\Song;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class SongResource extends Resource
{
    protected static ?string $model = Song::class;

    protected static string|BackedEnum|null $navigationIcon = 'iconoir-music-double-note';

    protected static string|UnitEnum|null $navigationGroup = 'Albums & Songs';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return SongForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SongInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SongsTable::configure($table);
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
            'index' => ListSongs::route('/'),
            'create' => CreateSong::route('/create'),
            'view' => ViewSong::route('/{record}'),
            'edit' => EditSong::route('/{record}/edit'),
        ];
    }
}
