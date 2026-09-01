<?php

namespace App\Livewire;

use App\Facades\TMDB;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('DobaPlay - Stream Movies, Series & Music From Africa & Beyond')]
#[Layout('layouts.marketing', [
    'metaDescription' => 'Watch movies and series, discover new East African music, and follow independent filmmakers and artists — all on DobaPlay. Stream now, or upload and monetise your own films and tracks.',
    'metaImage' => 'og/home-og.png',
    'keywords' => 'doba, dobaplay, doba play, dobaplay kenya, watch movies online kenya, stream movies africa, african movies online, kenyan movies streaming, african music streaming, dobaplay login, dobaplay register, dobaplay pricing, music distribution kenya, music distribution east africa, upload music online kenya, platform for musicians in kenya, music royalties payout m-pesa, upload films kenya, filmmaker distribution africa',
    'jsonLd' => null, // Organization schema already covers the homepage sitewide
])]
class Home extends Component
{
    /**
     * "Now Streaming" — cinematic hero carousel. Populated from TMDB's
     * daily trending list via TMDB::homeFeed(); falls back to a small
     * hand-picked static set if the TMDB key is missing or the request
     * fails.
     */
    public array $heroFeatures = [];

    /** "Trending Today" — landscape rail, trending/day. */
    public array $trendingToday = [];

    /** "Trending This Week" — landscape rail, trending/week. */
    public array $trendingWeek = [];

    /** "New Releases" — landscape rail, now_playing. */
    public array $newReleases = [];

    /** "Popular" — landscape rail, TMDB's general popularity ranking. */
    public array $popularMovies = [];

    /** "Top Rated" — landscape rail, TMDB's top_rated. */
    public array $topRatedMovies = [];

    /** "New Episodes" — TV shows currently on air. */
    public array $newEpisodes = [];

    /** TV counterpart data for per-rail toggles. */
    public array $tvTrendingToday = [];

    public array $tvTrendingWeek = [];

    public array $tvPopular = [];

    public array $tvTopRated = [];

    /**
     * Per-rail content type toggles.
     * Each rail independently switches between 'movies' and 'tv'.
     * Persisted in the URL so sharing or refreshing keeps the user's choice.
     */
    #[Url]
    public string $trendingTodayType = 'movies';

    #[Url]
    public string $trendingWeekType = 'movies';

    #[Url]
    public string $topRatedType = 'movies';

    #[Url]
    public string $popularType = 'movies';

    /**
     * "Continue Watching" — landscape cards with progress.
     * Populated from localStorage via Alpine.js on the frontend.
     */
    public array $continueWatching = [];

    /**
     * "Music" — stream the sounds behind the stories. Fictional demo
     * catalogue (no real artist names) using existing /home/ artwork
     * until real cover art and a catalogue exist.
     */
    public array $musicRails = [
        ['artist' => 'Baraka Sound', 'track' => 'Jua Kali', 'cover' => 'music.png', 'duration' => '3:24'],
        ['artist' => 'Zawadi', 'track' => 'Pwani Nights', 'cover' => 'record.png', 'duration' => '3:10'],
        ['artist' => 'The Matatu Crew', 'track' => 'Route 44', 'cover' => 'studio.png', 'duration' => '4:02'],
        ['artist' => 'Nia Wolde', 'track' => 'Amani', 'cover' => 'event.png', 'duration' => '3:47'],
        ['artist' => 'Kito Beats', 'track' => 'Frequency', 'cover' => 'music.png', 'duration' => '3:15'],
        ['artist' => 'Amani & The Signal', 'track' => 'Mwenda', 'cover' => 'record.png', 'duration' => '2:58'],
    ];

    /**
     * "The Crate" — creator plans, in display order.
     * Each entry drives one record-card in the pricing rail.
     */
    public array $plans = [
        [
            'key' => 'events',
            'title' => 'EVENTS',
            'price' => '499',
            'icon' => 'event.png',
            'spine' => 'freq',
            'cta_bg' => 'var(--color-frequency)',
            'cta_text' => 'var(--color-ink)',
            'popular' => false,
            'features' => [
                'Sell tickets online',
                'Promote your event',
                'Reach the right audience',
                'Merch store integration',
            ],
        ],
        [
            'key' => 'artist_video',
            'title' => 'ARTIST + VIDEOS',
            'price' => '1,499',
            'icon' => 'music.png',
            'spine' => 'sun',
            'cta_bg' => 'var(--color-sunburst)',
            'cta_text' => 'var(--color-ink)',
            'popular' => true,
            'features' => [
                'Music & video uploads',
                'Streaming earnings',
                'Video monetization',
                'Artist analytics',
                'Promote events',
                'Merch store integration',
            ],
        ],
        [
            'key' => 'studio',
            'title' => 'STUDIO',
            'price' => '14,999',
            'icon' => 'studio.png',
            'spine' => 'mus',
            'cta_bg' => 'var(--color-mustard)',
            'cta_text' => 'var(--color-ink)',
            'popular' => false,
            'features' => [
                '10 artist accounts',
                'Music & video uploads',
                'Studio dashboard',
                'Promote events',
                'Sell beats & get producing gigs',
            ],
        ],
        [
            'key' => 'record_label',
            'title' => 'RECORD LABEL',
            'price' => '49,999',
            'icon' => 'record.png',
            'spine' => 'bone',
            'cta_bg' => 'var(--color-bone)',
            'cta_text' => 'var(--color-ink)',
            'popular' => false,
            'features' => [
                'Unlimited artists',
                'All studio features',
                'Label payouts & analytics',
                'Promote events',
            ],
        ],
        [
            'key' => 'cinema',
            'title' => 'CINEMA',
            'price' => '3,999',
            'icon' => 'cinema-2.png',
            'spine' => 'hib',
            'cta_bg' => 'var(--color-hibiscus)',
            'cta_text' => 'var(--color-bone)',
            'popular' => false,
            'features' => [
                'VOD distribution',
                'Pay-per-view & rentals',
                'Subtitle support',
                'Premiere promotion',
            ],
        ],
    ];

    /**
     * "The Signal Chain" — feature strip explaining why the yearly plan matters.
     */
    public array $signalChain = [
        [
            'title' => 'SECURE STORAGE',
            'body' => 'Your masters and video files, backed up and protected.',
            'icon' => 'storage',
        ],
        [
            'title' => 'FAST STREAMING',
            'body' => 'Low-latency delivery built for East African networks.',
            'icon' => 'bolt',
        ],
        [
            'title' => 'PLATFORM SECURITY',
            'body' => 'Account protection and content safeguards, always on.',
            'icon' => 'shield',
        ],
        [
            'title' => 'COPYRIGHT CHECKS',
            'body' => 'Automatic detection keeps your originals protected.',
            'icon' => 'copyright',
        ],
        [
            'title' => 'M-PESA PAYOUTS',
            'body' => 'Earnings land in your pocket, no waiting on invoices.',
            'icon' => 'wallet',
        ],
    ];

    public function mount(): void
    {
        $feed = TMDB::homeFeed();

        $this->heroFeatures = $feed['hero'] ?: $this->fallbackHero();
        $this->trendingToday = $feed['trending_today'] ?: $this->fallbackTrending();
        $this->trendingWeek = $feed['trending_week'] ?: $this->fallbackTrending();
        $this->newReleases = $feed['now_playing'] ?: $this->fallbackNewReleases();
        $this->popularMovies = $feed['popular'] ?: $this->fallbackMovieGrid();
        $this->topRatedMovies = $feed['top_rated'] ?: $this->fallbackMovieGrid();

        // TV data
        $tvFeed = TMDB::tvHomeFeed();
        $this->tvTrendingToday = $tvFeed['trending_today'] ?: [];
        $this->tvTrendingWeek = $tvFeed['trending_week'] ?: [];
        $this->tvPopular = $tvFeed['popular'] ?: [];
        $this->tvTopRated = $tvFeed['top_rated'] ?: [];
        $this->newEpisodes = TMDB::tvOnTheAir() ?: $this->fallbackTrending();
    }

    /**
     * Switch a rail between movies and tv content.
     */
    public function switchRail(string $rail, string $type): void
    {
        if (! in_array($type, ['movies', 'tv'])) {
            return;
        }

        match ($rail) {
            'trendingToday' => $this->trendingTodayType = $type,
            'trendingWeek' => $this->trendingWeekType = $type,
            'topRated' => $this->topRatedType = $type,
            'popular' => $this->popularType = $type,
            default => null,
        };
    }

    /**
     * Populate Continue Watching from localStorage data sent by the frontend.
     */
    public function loadContinueWatching(array $items): void
    {
        $this->continueWatching = collect($items)
            ->take(8)
            ->map(fn ($item) => [
                'title' => $item['title'] ?? 'Untitled',
                'image' => $item['image'] ?? '',
                'remaining' => $this->formatRemaining($item['progress'] ?? 0),
                'progress' => $item['progress'] ?? 0,
                'tmdb_id' => $item['tmdbId'] ?? 0,
                'type' => $item['type'] ?? 'movie',
                'season' => $item['season'] ?? null,
                'episode' => $item['episode'] ?? null,
                'watch_href' => $this->buildWatchHref($item),
            ])
            ->all();
    }

    protected function buildWatchHref(array $item): string
    {
        if (($item['type'] ?? 'movie') === 'tv') {
            return route('watch.tv', [
                $item['tmdbId'] ?? 0,
                $item['season'] ?? 1,
                $item['episode'] ?? 1,
            ]);
        }

        return route('watch.movie', $item['tmdbId'] ?? 0);
    }

    protected function formatRemaining(int $progress): string
    {
        $remaining = (int) round((100 - $progress) * 1.2);
        $hours = intdiv($remaining, 60);
        $mins = $remaining % 60;

        if ($hours > 0) {
            return "{$hours}h {$mins}m remaining";
        }

        return "{$mins}m remaining";
    }

    public function render(): Factory|View|\Illuminate\View\View
    {
        return view('livewire.⚡home');
    }

    /**
     * Local fallback for the hero carousel — used when TMDB_API_KEY isn't
     * configured or the request fails. Reuses assets already shipped in
     * public/movies/images/ so the carousel still has several slides.
     */
    protected function fallbackHero(): array
    {
        return collect([
            [
                'tmdb_id' => 76600,
                'kicker' => 'DOBAPLAY PICK · NOW STREAMING',
                'title' => 'THE COVENANT',
                'tagline' => "A soldier's promise becomes a race against time, loyalty, and betrayal.",
                'meta' => ['2023', 'War · Drama', '2h 3m', '16+'],
                'image' => 'the covenant.PNG',
            ],
            [
                'tmdb_id' => 603,
                'kicker' => 'TRENDING TODAY',
                'title' => 'JOHN WICK',
                'tagline' => 'An ex-hitman comes out of retirement to track down the gangsters that took everything from him.',
                'meta' => ['2023', 'Action · Crime', '16+'],
                'image' => 'wick.PNG',
            ],
            [
                'tmdb_id' => 315162,
                'kicker' => 'TRENDING TODAY',
                'title' => 'GODZILLA',
                'tagline' => 'A colossal force of nature resurfaces, and humanity must find a way to survive.',
                'meta' => ['2023', 'Action · Sci-Fi', '13+'],
                'image' => 'godzilla.PNG',
            ],
            [
                'tmdb_id' => 872585,
                'kicker' => 'TRENDING TODAY',
                'title' => 'OPPENHEIMER',
                'tagline' => 'The story of J. Robert Oppenheimer and the race to build the atomic bomb.',
                'meta' => ['2023', 'Drama · Biography', '16+'],
                'image' => 'oppenheimer.PNG',
            ],
        ])->map(fn ($item) => [
            ...$item,
            'image' => asset('movies/images/'.$item['image']),
            'poster' => asset('movies/images/'.$item['image']),
            'watch_href' => route('watch.movie', $item['tmdb_id'] ?? 0),
        ])->all();
    }

    protected function fallbackTrending(): array
    {
        return $this->localiseImages([
            ['title' => 'Solo Leveling', 'image' => 'solo leveling.PNG', 'year' => '2024', 'genre' => 'Anime · Action', 'score' => 85, 'badge' => 'TOP 10'],
            ['title' => 'Godzilla', 'image' => 'godzilla.PNG', 'year' => '2023', 'genre' => 'Action · Sci-Fi', 'score' => 72, 'badge' => null],
            ['title' => 'The Meg', 'image' => 'the meg.PNG', 'year' => '2023', 'genre' => 'Action · Thriller', 'score' => 68, 'badge' => null],
            ['title' => 'John Wick', 'image' => 'wick.PNG', 'year' => '2023', 'genre' => 'Action · Crime', 'score' => 79, 'badge' => 'TRENDING'],
            ['title' => 'Oppenheimer', 'image' => 'oppenheimer.PNG', 'year' => '2023', 'genre' => 'Drama · Biography', 'score' => 88, 'badge' => null],
            ['title' => 'Sakamoto Days', 'image' => 'sakamoto days.PNG', 'year' => '2024', 'genre' => 'Anime · Action', 'score' => 82, 'badge' => 'NEW'],
            ['title' => 'Black Clover', 'image' => 'black clover.jpg', 'year' => '2023', 'genre' => 'Anime · Fantasy', 'score' => 76, 'badge' => null],
            ['title' => 'Game of Thrones', 'image' => 'game of thrones.PNG', 'year' => 'Series', 'genre' => 'Fantasy · Drama', 'score' => 91, 'badge' => null],
        ]);
    }

    protected function fallbackNewReleases(): array
    {
        return $this->localiseImages([
            ['title' => 'Dhurandhar: The Revenge', 'image' => 'Dhurandhar-_The_Revenge_poster.jpg.webp', 'year' => '2026', 'genre' => 'Action · Thriller', 'score' => null, 'badge' => 'NEW'],
            ['title' => 'El Camino', 'image' => 'el camino.PNG', 'year' => '2019', 'genre' => 'Drama · Crime', 'score' => 74, 'badge' => null],
            ['title' => 'War Machine', 'image' => 'war_machine.jpg', 'year' => '2017', 'genre' => 'War · Drama', 'score' => 65, 'badge' => null],
            ['title' => 'Mosul', 'image' => 'mosul.PNG', 'year' => '2019', 'genre' => 'War · Action', 'score' => 71, 'badge' => null],
            ['title' => 'Priest', 'image' => 'priest.PNG', 'year' => '2011', 'genre' => 'Action · Horror', 'score' => 58, 'badge' => null],
            ['title' => 'Van Helsing', 'image' => 'van hellsing.PNG', 'year' => '2004', 'genre' => 'Fantasy · Action', 'score' => 55, 'badge' => null],
            ['title' => 'IT', 'image' => 'IT.PNG', 'year' => '2017', 'genre' => 'Horror', 'score' => 73, 'badge' => null],
            ['title' => 'Slender Man', 'image' => 'slender man.PNG', 'year' => '2018', 'genre' => 'Horror', 'score' => 42, 'badge' => null],
        ]);
    }

    protected function fallbackMovieGrid(): array
    {
        return $this->localiseImages([
            ['title' => '300', 'image' => '300.jpg', 'year' => '2006', 'genre' => 'Action · War', 'score' => 72, 'badge' => null],
            ['title' => 'Ninja Assassin', 'image' => 'ninja assasin.PNG', 'year' => '2009', 'genre' => 'Action · Thriller', 'score' => 58, 'badge' => null],
            ['title' => 'Shooter', 'image' => 'shooter.PNG', 'year' => '2007', 'genre' => 'Action · Thriller', 'score' => 75, 'badge' => null],
            ['title' => 'Warcraft', 'image' => 'warcraft.jpg', 'year' => '2016', 'genre' => 'Fantasy · Action', 'score' => 68, 'badge' => null],
            ['title' => 'The Irishman', 'image' => 'the irishman.PNG', 'year' => '2019', 'genre' => 'Crime · Drama', 'score' => 80, 'badge' => null],
            ['title' => 'Spiderman', 'image' => 'spiderman.PNG', 'year' => '2002', 'genre' => 'Action · Sci-Fi', 'score' => 73, 'badge' => null],
            ['title' => 'Megalodon', 'image' => 'megaladon.PNG', 'year' => '2018', 'genre' => 'Action · Thriller', 'score' => 52, 'badge' => null],
            ['title' => 'Van Helsing', 'image' => 'van hellsing.PNG', 'year' => '2004', 'genre' => 'Fantasy · Action', 'score' => 55, 'badge' => null],
        ]);
    }

    /**
     * Resolves each demo item's 'image' filename against
     * public/movies/images/ so fallback rails render an absolute URL
     * exactly like the TMDB-backed ones — Blade doesn't need to know or
     * care which source a given rail came from.
     */
    protected function localiseImages(array $items): array
    {
        return collect($items)
            ->map(fn ($item) => [
                ...$item,
                'image' => asset('movies/images/'.$item['image']),
            ])
            ->all();
    }
}
