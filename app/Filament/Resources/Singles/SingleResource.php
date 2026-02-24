<?php

namespace App\Filament\Resources\Singles;

use App\Filament\Resources\Singles\Pages\CreateSingle;
use App\Filament\Resources\Singles\Pages\EditSingle;
use App\Filament\Resources\Singles\Pages\ListSingles;
use App\Filament\Resources\Singles\Pages\ViewSingle;
use App\Filament\Resources\Singles\Schemas\SingleForm;
use App\Filament\Resources\Singles\Schemas\SingleInfolist;
use App\Filament\Resources\Singles\Tables\SinglesTable;
use App\Models\Single;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SingleResource extends Resource
{
    protected static ?string $model = Single::class;

    protected static string|BackedEnum|null $navigationIcon = 'hugeicons-music-note-01';
    protected static string | UnitEnum | null $navigationGroup = 'Albums & Songs';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return SingleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SingleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SinglesTable::configure($table);
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
            'index' => ListSingles::route('/'),
            'create' => CreateSingle::route('/create'),
            'view' => ViewSingle::route('/{record}'),
            'edit' => EditSingle::route('/{record}/edit'),
        ];
    }
}
