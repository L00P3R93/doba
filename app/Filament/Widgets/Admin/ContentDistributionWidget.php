<?php

namespace App\Filament\Widgets\Admin;

use App\Models\Album;
use App\Models\Ep;
use App\Models\Podcast;
use App\Models\Single;
use App\Models\Song;
use Filament\Widgets\ChartWidget;

class ContentDistributionWidget extends ChartWidget
{
    protected static ?int $sort = 4;

    protected ?string $heading = 'Content Distribution';

    protected ?string $description = 'Breakdown of content types';

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $albums = Album::query()->count();
        $songs = Song::query()->count();
        $eps = Ep::query()->count();
        $podcasts = Podcast::query()->count();
        $singles = Single::query()->count();

        return [
            'datasets' => [
                [
                    'label' => 'Content Count',
                    'data' => [$albums, $songs, $eps, $podcasts, $singles],
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(251, 146, 60, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                    ],
                    'borderColor' => [
                        'rgba(59, 130, 246, 1)',
                        'rgba(34, 197, 94, 1)',
                        'rgba(251, 146, 60, 1)',
                        'rgba(168, 85, 247, 1)',
                        'rgba(236, 72, 153, 1)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['Albums', 'Songs', 'EPs', 'Podcasts', 'Singles'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
