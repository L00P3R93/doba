<?php

namespace App\Filament\Resources\Albums;

use App\Filament\Resources\Albums\Pages\CreateAlbum;
use App\Filament\Resources\Albums\Pages\EditAlbum;
use App\Filament\Resources\Albums\Pages\ListAlbums;
use App\Filament\Resources\Albums\Pages\ViewAlbum;
use App\Filament\Resources\Albums\RelationManagers\SongsRelationManager;
use App\Filament\Resources\Albums\Schemas\AlbumForm;
use App\Filament\Resources\Albums\Schemas\AlbumInfolist;
use App\Filament\Resources\Albums\Tables\AlbumsTable;
use App\Models\Album;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AlbumResource extends Resource
{
    protected static ?string $model = Album::class;

    protected static string|BackedEnum|null $navigationIcon = 'iconoir-album-open';
    protected static string | UnitEnum | null $navigationGroup = 'Albums & Songs';
    protected static ?int $navigationSort = 0;
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return AlbumForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AlbumInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AlbumsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SongsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAlbums::route('/'),
            'create' => CreateAlbum::route('/create'),
            'view' => ViewAlbum::route('/{record}'),
            'edit' => EditAlbum::route('/{record}/edit'),
        ];
    }
}
