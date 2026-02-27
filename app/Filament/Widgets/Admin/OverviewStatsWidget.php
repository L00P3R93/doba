<?php

namespace App\Filament\Widgets\Admin;

use App\Models\Album;
use App\Models\Ep;
use App\Models\Payment;
use App\Models\Podcast;
use App\Models\Single;
use App\Models\Song;
use App\Models\User;
use App\Models\UserSubscription;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverviewStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalUsers = User::query()->count();
        $activeUsers = User::query()->where('status', 'active')->count();
        $totalRevenue = Payment::query()->where('status', 'completed')->sum('amount');
        $activeSubscriptions = UserSubscription::query()->where('status', 'active')->count();

        $totalAlbums = Album::query()->count();
        $totalSongs = Song::query()->count();
        $totalEps = Ep::query()->count();
        $totalPodcasts = Podcast::query()->count();
        $totalSingles = Single::query()->count();

        $monthlyRevenue = Payment::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            Stat::make('Total Users', number_format($totalUsers))
                ->description($activeUsers.' active')
                ->descriptionIcon('heroicon-m-users')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary'),

            Stat::make('Total Revenue', 'KES '.number_format($totalRevenue, 2))
                ->description('KES '.number_format($monthlyRevenue, 2).' this month')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([17, 15, 4, 17, 15, 4, 17])
                ->color('success'),

            Stat::make('Active Subscriptions', number_format($activeSubscriptions))
                ->description('Monthly recurring')
                ->descriptionIcon('heroicon-m-credit-card')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),

            Stat::make('Total Content', number_format($totalAlbums + $totalSongs + $totalEps + $totalPodcasts + $totalSingles))
                ->description($totalAlbums.' albums, '.$totalSongs.' songs')
                ->descriptionIcon('heroicon-m-musical-note')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info'),

            Stat::make('New Users', number_format($newUsersThisMonth))
                ->description('This month')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success'),

            Stat::make('User Growth', $totalUsers > 0 ? round(($newUsersThisMonth / $totalUsers) * 100, 1).'%' : '0%')
                ->description('Monthly growth rate')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),
        ];
    }
}
