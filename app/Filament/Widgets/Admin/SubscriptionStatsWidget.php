<?php

namespace App\Filament\Widgets\Admin;

use App\Models\UserSubscription;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class SubscriptionStatsWidget extends ChartWidget
{
    protected static ?int $sort = 5;

    protected ?string $heading = 'Subscription Trends';

    protected ?string $description = 'Active subscriptions over time';

    protected ?string $maxHeight = '300px';

    public ?string $filter = '30days';

    protected function getData(): array
    {
        $filter = $this->filter;

        $match = match ($filter) {
            '7days' => now()->subDays(7),
            '30days' => now()->subDays(30),
            '90days' => now()->subDays(90),
            '1year' => now()->subYear(),
            default => now()->subDays(30),
        };

        // Get subscription counts using Trend
        $subscriptionsData = Trend::model(UserSubscription::class)
            ->between(
                start: $match,
                end: now(),
            )
            ->perDay()
            ->count();

        // Get revenue data using a completely custom query
        $revenueResults = UserSubscription::query()
            ->join('subscriptions', 'user_subscriptions.subscription_id', '=', 'subscriptions.id')
            ->whereBetween('user_subscriptions.created_at', [$match, now()])
            ->selectRaw('DATE(user_subscriptions.created_at) as date, SUM(subscriptions.price) as aggregate')
            ->groupByRaw('DATE(user_subscriptions.created_at)')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return new TrendValue(
                    date: $item->date,
                    aggregate: $item->aggregate
                );
            });

        return [
            'datasets' => [
                [
                    'label' => 'Active Subscriptions',
                    'data' => $subscriptionsData->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(251, 146, 60, 0.2)',
                    'borderColor' => 'rgba(251, 146, 60, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.3,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Revenue (KES)',
                    'data' => $revenueResults->map(fn (TrendValue $value) => $value->aggregate ?? 0),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 2,
                    'fill' => false,
                    'tension' => 0.3,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $subscriptionsData->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            '7days' => 'Last 7 days',
            '30days' => 'Last 30 days',
            '90days' => 'Last 90 days',
            '1year' => 'Last year',
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'title' => [
                        'display' => true,
                        'text' => 'Subscriptions',
                    ],
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'title' => [
                        'display' => true,
                        'text' => 'Revenue (KES)',
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
            ],
        ];
    }
}
