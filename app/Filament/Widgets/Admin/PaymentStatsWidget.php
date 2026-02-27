<?php

namespace App\Filament\Widgets\Admin;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PaymentStatsWidget extends ChartWidget
{
    protected static ?int $sort = 6;

    protected ?string $heading = 'Payment Analytics';

    protected ?string $description = 'Payment success rates and trends';

    protected ?string $maxHeight = '300px';

    public ?string $filter = '30days';

    protected function getData(): array
    {
        $filter = $this->filter;

        $match = match ($filter) {
            '7days' => now()->subDays(7),
            '90days' => now()->subDays(90),
            '1year' => now()->subYear(),
            default => now()->subDays(30),
        };

        $completedPayments = Trend::model(Payment::class)
            ->between(
                start: $match,
                end: now(),
            )
            ->perDay()
            ->count();

        $failedPayments = Trend::model(Payment::class)
            ->between(
                start: $match,
                end: now(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Completed Payments',
                    'data' => $completedPayments->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.8)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Failed Payments',
                    'data' => $failedPayments->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(239, 68, 68, 0.8)',
                    'borderColor' => 'rgba(239, 68, 68, 1)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $completedPayments->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
}
