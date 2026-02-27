<?php

namespace App\Filament\Widgets\Admin;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class RevenueChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Revenue Overview';

    protected ?string $description = 'Monthly revenue trends';

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '300px';

    public ?string $filter = '6months';

    protected function getData(): array
    {
        $filter = $this->filter;

        $match = match ($filter) {
            '1month' => now()->subMonth(),
            '3months' => now()->subMonths(3),
            '1year' => now()->subYear(),
            default => now()->subMonths(6),
        };

        $data = Trend::model(Payment::class)
            ->between(
                start: $match,
                end: now(),
            )
            ->perMonth()
            ->sum('amount');

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (KES)',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            '1month' => 'Last month',
            '3months' => 'Last 3 months',
            '6months' => 'Last 6 months',
            '1year' => 'Last year',
        ];
    }
}
