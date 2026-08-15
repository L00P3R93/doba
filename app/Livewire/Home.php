<?php

namespace App\Livewire;

use App\Facades\TMDB;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
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

    /**
     * "Movies" — the genre-filterable browse rail. Starts as $popularMovies
     * ("All") and swaps to a byGenre()/byKeyword()/byOriginCountry() result
     * whenever a chip is clicked. See selectGenre() below.
     */
    public array $movieGrid = [];

    /** Currently selected "Movies" genre chip. */
    public string $activeGenre = 'All';

    /**
     * "Continue Watching" — landscape cards with demo progress.
     * Static placeholder data, shaped so it's a drop-in swap for real
     * watch-history later. TMDB has no concept of "your" history, so
     * this stays local regardless of TMDB availability.
     */
    public array $continueWatching = [
        ['title' => 'John Wick', 'image' => 'wick.PNG', 'remaining' => '1h 18m remaining', 'progress' => 62],
        ['title' => 'The Wolf of Wall Street', 'image' => 'the wolf of wall street.PNG', 'remaining' => '42m remaining', 'progress' => 78],
        ['title' => 'Terminator: Dark Fate', 'image' => 'terimator.PNG', 'remaining' => '55m remaining', 'progress' => 30],
        ['title' => 'Zodiac', 'image' => 'zodiac.PNG', 'remaining' => '1h 40m remaining', 'progress' => 15],
    ];

    /**
     * "Movies" — genre filter chips shown to the user. Keys in
     * $genreFilters below must match these labels exactly.
     */
    public array $movieGenres = ['All', 'Action', 'Drama', 'Thriller', 'Anime', 'Sci-Fi', 'Horror', 'War', 'African Cinema'];

    /**
     * Maps each genre chip label to how it should query TMDB:
     *  - 'genre'   -> TMDB::byGenre($id)              (standard TMDB genre id)
     *  - 'keyword' -> TMDB::byKeyword($id)             (TMDB keyword id — for
     *                 chips with no clean matching genre, e.g. Anime)
     *  - 'origin'  -> TMDB::byOriginCountry($countries) (production country —
     *                 for "African Cinema", which isn't a TMDB genre at all)
     *  - 'popular' -> falls back to $popularMovies      ("All")
     */
    protected array $genreFilters = [
        'All' => ['type' => 'popular'],
        'Action' => ['type' => 'genre', 'id' => 28],
        'Drama' => ['type' => 'genre', 'id' => 18],
        'Thriller' => ['type' => 'genre', 'id' => 53],
        'Anime' => ['type' => 'keyword', 'id' => 210024], // TMDB's canonical "anime" keyword
        'Sci-Fi' => ['type' => 'genre', 'id' => 878],
        'Horror' => ['type' => 'genre', 'id' => 27],
        'War' => ['type' => 'genre', 'id' => 10752],
        'African Cinema' => ['type' => 'origin', 'countries' => ['KE', 'NG', 'ZA', 'GH', 'ET']],
    ];

    /**
     * "Trailers" — click-to-play preview rail. Locally hosted files;
     * TMDB's /videos endpoint would need a separate opt-in fetch per
     * title, so this stays static until that's worth the extra calls.
     */
    public array $trailers = [
        ['title' => 'Black Clover: Sword of the Wizard King', 'video' => 'black-clover-sword-of-the-wizard-king.mp4', 'poster' => 'black clover.jpg'],
        ['title' => 'Game of Thrones — Season 7', 'video' => 'game-of-thrones-s7.mp4', 'poster' => 'game of thrones.PNG'],
        ['title' => 'Sakamoto Days — Season 2 Announcement', 'video' => 'sakamoto-days-season-2-announcement-netflix-720-ytshorts.savetube.me.mp4', 'poster' => 'sakamoto days.PNG'],
        ['title' => 'Solo Leveling — Season 3', 'video' => 'solo-leveling-season-3.mp4', 'poster' => 'solo leveling.PNG'],
        ['title' => 'Terminator: Dark Fate — Official Trailer', 'video' => 'terminator-dark-fate-official-trailer.mp4', 'poster' => 'terimator.PNG'],
    ];

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

        // "Movies" starts on the "All" chip, which is just $popularMovies.
        $this->movieGrid = $this->popularMovies;
    }

    /**
     * Genre chip click handler. Re-queries TMDB (through its own cache —
     * see TMDBService::byGenre()/byKeyword()/byOriginCountry()) for the
     * selected chip and swaps $movieGrid. "All" is free — it just points
     * back at the already-loaded $popularMovies instead of firing a
     * request.
     */
    public function selectGenre(string $genre): void
    {
        if (! array_key_exists($genre, $this->genreFilters)) {
            return;
        }

        $this->activeGenre = $genre;
        $filter = $this->genreFilters[$genre];

        $results = match ($filter['type']) {
            'genre' => TMDB::byGenre($filter['id'], 12),
            'keyword' => TMDB::byKeyword($filter['id'], 12),
            'origin' => TMDB::byOriginCountry($filter['countries'], 12),
            default => $this->popularMovies,
        };

        $this->movieGrid = $results ?: $this->fallbackMovieGrid();
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
                'kicker' => 'DOBAPLAY PICK · NOW STREAMING',
                'title' => 'THE COVENANT',
                'tagline' => "A soldier's promise becomes a race against time, loyalty, and betrayal.",
                'meta' => ['2023', 'War · Drama', '2h 3m', '16+'],
                'image' => 'the covenant.PNG',
            ],
            [
                'kicker' => 'TRENDING TODAY',
                'title' => 'JOHN WICK',
                'tagline' => 'An ex-hitman comes out of retirement to track down the gangsters that took everything from him.',
                'meta' => ['2023', 'Action · Crime', '16+'],
                'image' => 'wick.PNG',
            ],
            [
                'kicker' => 'TRENDING TODAY',
                'title' => 'GODZILLA',
                'tagline' => 'A colossal force of nature resurfaces, and humanity must find a way to survive.',
                'meta' => ['2023', 'Action · Sci-Fi', '13+'],
                'image' => 'godzilla.PNG',
            ],
            [
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
            'watch_href' => '#trending-today',
        ])->all();
    }

    protected function fallbackTrending(): array
    {
        return $this->localiseImages([
            ['title' => 'Solo Leveling', 'image' => 'solo leveling.PNG', 'year' => '2024', 'genre' => 'Anime · Action', 'badge' => 'TOP 10'],
            ['title' => 'Godzilla', 'image' => 'godzilla.PNG', 'year' => '2023', 'genre' => 'Action · Sci-Fi', 'badge' => null],
            ['title' => 'The Meg', 'image' => 'the meg.PNG', 'year' => '2023', 'genre' => 'Action · Thriller', 'badge' => null],
            ['title' => 'John Wick', 'image' => 'wick.PNG', 'year' => '2023', 'genre' => 'Action · Crime', 'badge' => 'TRENDING'],
            ['title' => 'Oppenheimer', 'image' => 'oppenheimer.PNG', 'year' => '2023', 'genre' => 'Drama · Biography', 'badge' => null],
            ['title' => 'Sakamoto Days', 'image' => 'sakamoto days.PNG', 'year' => '2024', 'genre' => 'Anime · Action', 'badge' => 'NEW'],
            ['title' => 'Black Clover', 'image' => 'black clover.jpg', 'year' => '2023', 'genre' => 'Anime · Fantasy', 'badge' => null],
            ['title' => 'Game of Thrones', 'image' => 'game of thrones.PNG', 'year' => 'Series', 'genre' => 'Fantasy · Drama', 'badge' => null],
        ]);
    }

    protected function fallbackNewReleases(): array
    {
        return $this->localiseImages([
            ['title' => 'Dhurandhar: The Revenge', 'image' => 'Dhurandhar-_The_Revenge_poster.jpg.webp', 'year' => '2026', 'genre' => 'Action · Thriller', 'badge' => 'NEW'],
            ['title' => 'El Camino', 'image' => 'el camino.PNG', 'year' => '2019', 'genre' => 'Drama · Crime', 'badge' => null],
            ['title' => 'War Machine', 'image' => 'war_machine.jpg', 'year' => '2017', 'genre' => 'War · Drama', 'badge' => null],
            ['title' => 'Mosul', 'image' => 'mosul.PNG', 'year' => '2019', 'genre' => 'War · Action', 'badge' => null],
            ['title' => 'Priest', 'image' => 'priest.PNG', 'year' => '2011', 'genre' => 'Action · Horror', 'badge' => null],
            ['title' => 'Van Helsing', 'image' => 'van hellsing.PNG', 'year' => '2004', 'genre' => 'Fantasy · Action', 'badge' => null],
            ['title' => 'IT', 'image' => 'IT.PNG', 'year' => '2017', 'genre' => 'Horror', 'badge' => null],
            ['title' => 'Slender Man', 'image' => 'slender man.PNG', 'year' => '2018', 'genre' => 'Horror', 'badge' => null],
        ]);
    }

    protected function fallbackMovieGrid(): array
    {
        return $this->localiseImages([
            ['title' => '300', 'image' => '300.jpg', 'year' => '2006', 'genre' => 'Action · War'],
            ['title' => 'Ninja Assassin', 'image' => 'ninja assasin.PNG', 'year' => '2009', 'genre' => 'Action · Thriller'],
            ['title' => 'Shooter', 'image' => 'shooter.PNG', 'year' => '2007', 'genre' => 'Action · Thriller'],
            ['title' => 'Warcraft', 'image' => 'warcraft.jpg', 'year' => '2016', 'genre' => 'Fantasy · Action'],
            ['title' => 'The Irishman', 'image' => 'the irishman.PNG', 'year' => '2019', 'genre' => 'Crime · Drama'],
            ['title' => 'Spiderman', 'image' => 'spiderman.PNG', 'year' => '2002', 'genre' => 'Action · Sci-Fi'],
            ['title' => 'Megalodon', 'image' => 'megaladon.PNG', 'year' => '2018', 'genre' => 'Action · Thriller'],
            ['title' => 'Van Helsing', 'image' => 'van hellsing.PNG', 'year' => '2004', 'genre' => 'Fantasy · Action'],
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
