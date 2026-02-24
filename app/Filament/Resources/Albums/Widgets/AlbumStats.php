<?php

namespace App\Filament\Resources\Albums\Widgets;

use App\Filament\Resources\Albums\Pages\ListAlbums;
use App\Models\Album;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AlbumStats extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    public array $tableColumnSearches = [];

    protected ?string $pollingInterval = '300s';

    protected function getTablePage(): string
    {
        return ListAlbums::class;
    }

    protected function getStats(): array
    {
        $query = Album::query();
        $totalAlbums = $query->count();
        $activeAlbums = $query->where('is_active', true)->count();
        $inactiveAlbums = $query->where('is_active', false)->count();
        $hotAlbums = $query->where('is_active', true)->where('hot_or_cold', true)->count();

        if (! auth()->user()->isAdmin()) {
            $totalAlbums = $query->where('user_id', auth()->user()->id)->count();
            $activeAlbums = $query->where('user_id', auth()->user()->id)->where('is_active', true)->count();
            $inactiveAlbums = $query->where('user_id', auth()->user()->id)->where('is_active', false)->count();
            $hotAlbums = $query->where('user_id', auth()->user()->id)->where('is_active', true)->where('hot_or_cold', true)->count();
        }


        $albumsData = Trend::model(Album::class)
            ->between(
                start: now()->startOfYear(),
                end: now()
            )
            ->perMonth()
            ->count();

        return [
            Stat::make('Total Albums', format_number($totalAlbums))
                ->icon('iconoir-album')
                ->description('Total number of albums')
                ->descriptionIcon(Heroicon::OutlinedArrowTrendingUp)
                ->chart($albumsData->map(fn (TrendValue $value) => $value->aggregate)->toArray())
                ->color('success'),
            Stat::make('Hot Albums', format_number($hotAlbums))
                ->icon('iconoir-album-carousel')
                ->description('Total number of hot albums')
                ->descriptionIcon(Heroicon::OutlinedArrowTrendingUp)
                ->color('warning'),
            Stat::make('Active Albums', format_number($activeAlbums))
                ->icon('iconoir-album-open')
                ->description('Total number of active albums')
                ->descriptionIcon(Heroicon::OutlinedArrowTrendingUp)
                ->color('info'),
            Stat::make('In Active Albums', format_number($inactiveAlbums))
                ->icon('iconoir-album-list')
                ->description('Total number of in active albums')
                ->descriptionIcon(Heroicon::OutlinedArrowTrendingUp)
                ->color('danger'),
        ];
    }
}
