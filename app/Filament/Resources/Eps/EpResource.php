<?php

namespace App\Filament\Resources\Eps;

use App\Filament\Resources\Eps\Pages\CreateEp;
use App\Filament\Resources\Eps\Pages\EditEp;
use App\Filament\Resources\Eps\Pages\ListEps;
use App\Filament\Resources\Eps\Pages\ViewEp;
use App\Filament\Resources\Eps\Schemas\EpForm;
use App\Filament\Resources\Eps\Schemas\EpInfolist;
use App\Filament\Resources\Eps\Tables\EpsTable;
use App\Models\Ep;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class EpResource extends Resource
{
    protected static ?string $model = Ep::class;

    protected static string|BackedEnum|null $navigationIcon = 'iconoir-album-list';

    protected static string|UnitEnum|null $navigationGroup = 'EPs & Songs';

    protected static ?int $navigationSort = 0;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return EpForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EpInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EpsTable::configure($table);
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
            'index' => ListEps::route('/'),
            'create' => CreateEp::route('/create'),
            'view' => ViewEp::route('/{record}'),
            'edit' => EditEp::route('/{record}/edit'),
        ];
    }
}
