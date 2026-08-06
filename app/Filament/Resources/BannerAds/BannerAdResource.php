<?php

namespace App\Filament\Resources\BannerAds;

use App\Filament\Resources\BannerAds\Pages\CreateBannerAd;
use App\Filament\Resources\BannerAds\Pages\EditBannerAd;
use App\Filament\Resources\BannerAds\Pages\ListBannerAds;
use App\Filament\Resources\BannerAds\Schemas\BannerAdForm;
use App\Filament\Resources\BannerAds\Tables\BannerAdsTable;
use App\Models\BannerAd;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class BannerAdResource extends Resource
{
    protected static ?string $model = BannerAd::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static string|UnitEnum|null $navigationGroup = 'Advertising';

    protected static ?int $navigationSort = 0;

    protected static ?string $recordTitleAttribute = 'headline';

    public static function form(Schema $schema): Schema
    {
        return BannerAdForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BannerAdsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBannerAds::route('/'),
            'create' => CreateBannerAd::route('/create'),
            'edit' => EditBannerAd::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('manage_banner_ads') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('manage_banner_ads') ?? false;
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()?->can('manage_banner_ads') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->can('manage_banner_ads') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->can('manage_banner_ads') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->can('manage_banner_ads') ?? false;
    }
}
