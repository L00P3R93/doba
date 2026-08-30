<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TMDBService
{
    protected string $baseUrl = 'https://api.themoviedb.org/3';

    protected string $imageBaseUrl = 'https://image.tmdb.org/t/p';

    protected ?string $apiKey;

    /**
     * Maps app-level genre labels to their TMDB IDs for movies and TV shows.
     * Movie and TV genres share some IDs but diverge for several categories.
     */
    protected array $genreMap = [
        'Action' => ['movie' => 28, 'tv' => 10759],
        'Drama' => ['movie' => 18, 'tv' => 18],
        'Thriller' => ['movie' => 53, 'tv' => 9648],
        'Sci-Fi' => ['movie' => 878, 'tv' => 10765],
        'Horror' => ['movie' => 27, 'tv' => null],
        'War' => ['movie' => 10752, 'tv' => 10768],
        'Comedy' => ['movie' => 35, 'tv' => 35],
        'Crime' => ['movie' => 80, 'tv' => 80],
        'Animation' => ['movie' => 16, 'tv' => 16],
        'Family' => ['movie' => 10751, 'tv' => 10751],
    ];

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.key');
    }

    /**
     * "Now Streaming" hero carousel — today's trending titles mapped into
     * the wide-backdrop hero shape the streaming homepage renders.
     */
    public function heroCarousel(int $limit = 6): array
    {
        return Cache::remember('tmdb:hero', now()->addHours(6), function () use ($limit) {
            $genres = $this->genres();

            return collect($this->get('/trending/movie/day')['results'] ?? [])
                ->filter(fn ($movie) => ! empty($movie['backdrop_path']))
                ->take($limit)
                ->values()
                ->map(function ($movie, $index) use ($genres) {
                    $genreNames = collect($movie['genre_ids'] ?? [])
                        ->map(fn ($id) => $genres[$id] ?? null)
                        ->filter()
                        ->take(2)
                        ->implode(' · ');

                    return [
                        'tmdb_id' => $movie['id'] ?? null,
                        'kicker' => $index === 0 ? 'DOBAPLAY PICK · TRENDING TODAY' : 'TRENDING TODAY',
                        'title' => Str::upper($movie['title'] ?? 'Untitled'),
                        'tagline' => Str::limit(trim($movie['overview'] ?? ''), 140),
                        'meta' => array_values(array_filter([
                            ! empty($movie['release_date']) ? substr($movie['release_date'], 0, 4) : null,
                            $genreNames ?: null,
                            isset($movie['vote_average']) && $movie['vote_average'] > 0
                                ? number_format($movie['vote_average'], 1).' ★'
                                : null,
                        ])),
                        'image' => "{$this->imageBaseUrl}/original{$movie['backdrop_path']}",
                        'poster' => ! empty($movie['poster_path'])
                            ? "{$this->imageBaseUrl}/w342{$movie['poster_path']}"
                            : "{$this->imageBaseUrl}/original{$movie['backdrop_path']}",
                        'watch_href' => route('watch.movie', $movie['id'] ?? 0),
                    ];
                })
                ->all();
        });
    }

    /**
     * "Trending Today" rail — trending/day. First three slots get a
     * "TOP 10" badge.
     */
    public function trendingToday(int $limit = 12): array
    {
        return Cache::remember('tmdb:trending-today', now()->addHours(3), function () use ($limit) {
            return $this->mapResults(
                $this->get('/trending/movie/day'),
                $limit,
                fn ($movie, $index) => $index < 3 ? 'TOP 10' : null
            );
        });
    }

    /**
     * "Trending This Week" rail — trending/week.
     */
    public function trendingThisWeek(int $limit = 12): array
    {
        return Cache::remember('tmdb:trending-week', now()->addHours(6), function () use ($limit) {
            return $this->mapResults(
                $this->get('/trending/movie/week'),
                $limit,
                fn ($movie, $index) => $index < 3 ? 'TOP 10' : null
            );
        });
    }

    /**
     * "New Releases" rail — currently in theaters / recently released.
     * Anything released in the last 21 days gets a "NEW" badge.
     */
    public function nowPlaying(int $limit = 12): array
    {
        return Cache::remember('tmdb:now-playing', now()->addHours(12), function () use ($limit) {
            return $this->mapResults(
                $this->get('/movie/now_playing', ['region' => 'KE']),
                $limit,
                function ($movie) {
                    $releaseDate = $movie['release_date'] ?? null;

                    return $releaseDate && now()->diffInDays($releaseDate) <= 21 ? 'NEW' : null;
                }
            );
        });
    }

    /**
     * "Popular" rail — TMDB's general popularity ranking. Broader and
     * more stable than the trending endpoints, which move daily/weekly.
     */
    public function popular(int $limit = 12): array
    {
        return Cache::remember('tmdb:popular', now()->addHours(6), function () use ($limit) {
            return $this->mapResults($this->get('/movie/popular'), $limit);
        });
    }

    public function topRated(int $limit = 12): array
    {
        return Cache::remember('tmdb:top-rated', now()->addDay(), function () use ($limit) {
            return $this->mapResults($this->get('/movie/top_rated'), $limit);
        });
    }

    /**
     * "Movies" rail filtered by a TMDB genre id — powers the genre chips.
     */
    public function byGenre(int $genreId, int $limit = 12): array
    {
        return Cache::remember("tmdb:genre:{$genreId}", now()->addDay(), function () use ($genreId, $limit) {
            return $this->mapResults($this->get('/discover/movie', [
                'with_genres' => $genreId,
                'sort_by' => 'popularity.desc',
            ]), $limit);
        });
    }

    /**
     * "Movies" rail filtered by a TMDB keyword id — used for chips that
     * don't map cleanly onto a genre (e.g. "Anime", which TMDB tracks as
     * the Animation genre + an "anime" keyword rather than its own genre).
     */
    public function byKeyword(int $keywordId, int $limit = 12): array
    {
        return Cache::remember("tmdb:keyword:{$keywordId}", now()->addDay(), function () use ($keywordId, $limit) {
            return $this->mapResults($this->get('/discover/movie', [
                'with_keywords' => $keywordId,
                'sort_by' => 'popularity.desc',
            ]), $limit);
        });
    }

    /**
     * "Movies" rail filtered by production origin country — used for the
     * "African Cinema" chip, which has no single corresponding TMDB genre.
     * Country codes are OR'd together (TMDB's `|` separator).
     *
     * @param  string[]  $countryCodes  ISO 3166-1 codes, e.g. ['KE','NG','ZA']
     */
    public function byOriginCountry(array $countryCodes, int $limit = 12): array
    {
        $key = 'tmdb:origin:'.implode('-', $countryCodes);

        return Cache::remember($key, now()->addDay(), function () use ($countryCodes, $limit) {
            return $this->mapResults($this->get('/discover/movie', [
                'with_origin_country' => implode('|', $countryCodes),
                'sort_by' => 'popularity.desc',
            ]), $limit);
        });
    }

    /**
     * TMDB genre id => name lookup, used to label cards.
     */
    public function genres(): array
    {
        return Cache::remember('tmdb:genres', now()->addWeek(), function () {
            $response = $this->get('/genre/movie/list');

            return collect($response['genres'] ?? [])->pluck('name', 'id')->all();
        });
    }

    /**
     * Bundles every TMDB-backed section the streaming homepage needs into
     * a single cached payload. On a warm cache this turns the homepage's
     * TMDB reads into one Cache::remember lookup instead of six — and
     * because each section underneath keeps its own cache key and TTL,
     * rebuilding this outer entry after it expires is usually just
     * re-reading those still-warm inner caches rather than hitting the
     * TMDB API again.
     */
    public function homeFeed(): array
    {
        return Cache::remember('tmdb:home-feed', now()->addHours(3), function () {
            return [
                'hero' => $this->heroCarousel(6),
                'trending_today' => $this->trendingToday(12),
                'trending_week' => $this->trendingThisWeek(12),
                'now_playing' => $this->nowPlaying(12),
                'popular' => $this->popular(12),
                'top_rated' => $this->topRated(12),
            ];
        });
    }

    // =========================================================================
    //  TV SHOW METHODS
    // =========================================================================

    /**
     * "TV Trending Today" rail — trending/tv/day. First three get "TOP 10".
     */
    public function tvTrendingToday(int $limit = 12): array
    {
        return Cache::remember('tmdb:tv:trending-today', now()->addHours(3), function () use ($limit) {
            return $this->mapTvResults(
                $this->get('/trending/tv/day'),
                $limit,
                fn (array $show, int $index) => $index < 3 ? 'TOP 10' : null
            );
        });
    }

    /**
     * "TV Trending This Week" rail — trending/tv/week.
     */
    public function tvTrendingThisWeek(int $limit = 12): array
    {
        return Cache::remember('tmdb:tv:trending-week', now()->addHours(6), function () use ($limit) {
            return $this->mapTvResults(
                $this->get('/trending/tv/week'),
                $limit,
                fn (array $show, int $index) => $index < 3 ? 'TOP 10' : null
            );
        });
    }

    /**
     * "TV Popular" rail — discover/tv sorted by popularity.
     */
    public function tvPopular(int $limit = 12): array
    {
        return Cache::remember('tmdb:tv:popular', now()->addHours(6), function () use ($limit) {
            return $this->mapTvResults(
                $this->get('/discover/tv', ['sort_by' => 'popularity.desc']),
                $limit
            );
        });
    }

    /**
     * "TV Top Rated" rail — discover/tv sorted by vote average.
     */
    public function tvTopRated(int $limit = 12): array
    {
        return Cache::remember('tmdb:tv:top-rated', now()->addDay(), function () use ($limit) {
            return $this->mapTvResults(
                $this->get('/discover/tv', ['sort_by' => 'vote_average.desc']),
                $limit
            );
        });
    }

    /**
     * "New Episodes" rail — TV shows currently on air.
     */
    public function tvOnTheAir(int $limit = 12): array
    {
        return Cache::remember('tmdb:tv:on-the-air', now()->addHours(6), function () use ($limit) {
            return $this->mapTvResults(
                $this->get('/tv/on_the_air'),
                $limit
            );
        });
    }

    /**
     * TV shows filtered by a TMDB genre id — powers the TV genre chips.
     */
    public function tvByGenre(int $genreId, int $limit = 12): array
    {
        return Cache::remember("tmdb:tv:genre:{$genreId}", now()->addDay(), function () use ($genreId, $limit) {
            return $this->mapTvResults(
                $this->get('/discover/tv', [
                    'with_genres' => $genreId,
                    'sort_by' => 'popularity.desc',
                ]),
                $limit
            );
        });
    }

    /**
     * TV shows filtered by production origin country.
     *
     * @param  string[]  $countryCodes  ISO 3166-1 codes, e.g. ['KE','NG','ZA']
     */
    public function tvByOriginCountry(array $countryCodes, int $limit = 12): array
    {
        $key = 'tmdb:tv:origin:'.implode('-', $countryCodes);

        return Cache::remember($key, now()->addDay(), function () use ($countryCodes, $limit) {
            return $this->mapTvResults(
                $this->get('/discover/tv', [
                    'with_origin_country' => implode('|', $countryCodes),
                    'sort_by' => 'popularity.desc',
                ]),
                $limit
            );
        });
    }

    /**
     * TMDB TV genre id => name lookup, used to label TV cards.
     */
    public function tvGenres(): array
    {
        return Cache::remember('tmdb:tv:genres', now()->addWeek(), function () {
            $response = $this->get('/genre/tv/list');

            return collect($response['genres'] ?? [])->pluck('name', 'id')->all();
        });
    }

    /**
     * Full TV show details with external IDs, credits, and videos.
     */
    public function tvDetail(int $id): ?array
    {
        return Cache::remember("tmdb:tv:detail:{$id}", now()->addDay(), function () use ($id) {
            $response = $this->get("/tv/{$id}", [
                'append_to_response' => 'external_ids,credits,videos',
            ]);

            return $response ?: null;
        });
    }

    /**
     * TV season details including all episodes.
     */
    public function tvSeason(int $seriesId, int $season): ?array
    {
        return Cache::remember("tmdb:tv:season:{$seriesId}:{$season}", now()->addWeek(), function () use ($seriesId, $season) {
            $response = $this->get("/tv/{$seriesId}/season/{$season}");

            return $response ?: null;
        });
    }

    /**
     * Movie details with external IDs, credits, and videos.
     */
    public function movieDetail(int $id): ?array
    {
        return Cache::remember("tmdb:movie:detail:{$id}", now()->addDay(), function () use ($id) {
            $response = $this->get("/movie/{$id}", [
                'append_to_response' => 'external_ids,credits,videos',
            ]);

            return $response ?: null;
        });
    }

    /**
     * Search TMDB for movies, TV shows, or both.
     *
     * @param  string  $query  Search term
     * @param  string  $type  'multi', 'movie', or 'tv'
     */
    public function search(string $query, string $type = 'multi', int $limit = 20): array
    {
        $cacheKey = 'tmdb:search:'.md5($query.':'.$type);

        return Cache::remember($cacheKey, now()->addHours(1), function () use ($query, $type, $limit) {
            $endpoint = $type === 'multi' ? '/search/multi' : "/search/{$type}";

            $response = $this->get($endpoint, ['query' => $query]);

            return collect($response['results'] ?? [])
                ->filter(fn (array $item) => in_array($item['media_type'] ?? '', ['movie', 'tv'], true))
                ->take($limit)
                ->values()
                ->map(function (array $item) {
                    $isTv = ($item['media_type'] ?? '') === 'tv';

                    return [
                        'tmdb_id' => $item['id'] ?? null,
                        'type' => $isTv ? 'tv' : 'movie',
                        'title' => $isTv ? ($item['name'] ?? 'Untitled') : ($item['title'] ?? 'Untitled'),
                        'image' => ! empty($item['backdrop_path'])
                            ? "{$this->imageBaseUrl}/w780{$item['backdrop_path']}"
                            : (! empty($item['poster_path']) ? "{$this->imageBaseUrl}/w500{$item['poster_path']}" : null),
                        'year' => $isTv
                            ? (! empty($item['first_air_date']) ? substr($item['first_air_date'], 0, 4) : 'TBA')
                            : (! empty($item['release_date']) ? substr($item['release_date'], 0, 4) : 'TBA'),
                        'overview' => Str::limit($item['overview'] ?? '', 200),
                        'vote_average' => $item['vote_average'] ?? 0,
                    ];
                })
                ->filter(fn (array $item) => $item['image'])
                ->values()
                ->all();
        });
    }

    /**
     * Resolve the TMDB ID for a given movie, fetching external IDs to get the IMDb ID.
     *
     * @return array{tmdb_id: int, imdb_id: string|null}
     */
    public function getExternalIds(string $type, int $id): ?array
    {
        $cacheKey = "tmdb:external:{$type}:{$id}";

        return Cache::remember($cacheKey, now()->addWeek(), function () use ($type, $id) {
            $response = $this->get("/{$type}/{$id}/external_ids");

            if (empty($response)) {
                return null;
            }

            return [
                'tmdb_id' => $id,
                'imdb_id' => $response['imdb_id'] ?? null,
            ];
        });
    }

    /**
     * Get the genre ID from the genre map for a given label and content type.
     */
    public function getGenreId(string $label, string $contentType = 'movie'): ?int
    {
        return $this->genreMap[$label][$contentType] ?? null;
    }

    /**
     * Get the full genre map.
     *
     * @return array<string, array{movie: int|null, tv: int|null}>
     */
    public function getGenreMap(): array
    {
        return $this->genreMap;
    }

    /**
     * Bundles every TMDB-backed TV section the homepage needs.
     */
    public function tvHomeFeed(): array
    {
        return Cache::remember('tmdb:tv:home-feed', now()->addHours(3), function () {
            return [
                'trending_today' => $this->tvTrendingToday(12),
                'trending_week' => $this->tvTrendingThisWeek(12),
                'popular' => $this->tvPopular(12),
                'top_rated' => $this->tvTopRated(12),
            ];
        });
    }

    // =========================================================================
    //  SHARED HELPERS
    // =========================================================================

    protected function get(string $endpoint, array $params = []): array
    {
        if (! $this->apiKey) {
            Log::warning('TMDB_API_KEY is not set; skipping TMDB request.');

            return [];
        }

        try {
            $response = Http::timeout(5)
                ->acceptJson()
                ->get($this->baseUrl.$endpoint, array_merge($params, [
                    'api_key' => $this->apiKey,
                    'language' => 'en-US',
                ]));

            return $response->successful() ? $response->json() : [];
        } catch (\Throwable $e) {
            Log::warning('TMDB request failed', ['endpoint' => $endpoint, 'error' => $e->getMessage()]);

            return [];
        }
    }

    /**
     * Maps raw TMDB movie results into the shape the Blade rails consume.
     * Prefers backdrop_path (landscape, 16:9) over poster_path (portrait,
     * 2:3) so every rail renders as a widescreen thumbnail — falls back
     * to the poster crop only if a title has no backdrop art at all.
     *
     * @param  (callable(array, int): (string|null))|null  $badgeResolver
     */
    protected function mapResults(array $response, int $limit, ?callable $badgeResolver = null): array
    {
        $genres = $this->genres();

        return collect($response['results'] ?? [])
            ->take($limit)
            ->values()
            ->map(fn ($movie, $index) => [
                'title' => $movie['title'] ?? 'Untitled',
                'image' => ! empty($movie['backdrop_path'])
                    ? "{$this->imageBaseUrl}/w780{$movie['backdrop_path']}"
                    : (! empty($movie['poster_path']) ? "{$this->imageBaseUrl}/w500{$movie['poster_path']}" : null),
                'year' => (! empty($movie['release_date']))
                    ? substr($movie['release_date'], 0, 4)
                    : 'TBA',
                'genre' => collect($movie['genre_ids'] ?? [])
                    ->map(fn ($id) => $genres[$id] ?? null)
                    ->filter()
                    ->take(2)
                    ->implode(' · ') ?: 'Movie',
                'score' => isset($movie['vote_average']) ? (int) round($movie['vote_average'] * 10) : null,
                'badge' => $badgeResolver ? $badgeResolver($movie, $index) : null,
                'source' => 'tmdb',
                'tmdb_id' => $movie['id'] ?? null,
            ])
            ->filter(fn ($m) => $m['image']) // skip anything with no usable art
            ->values()
            ->all();
    }

    /**
     * Maps raw TMDB TV results into the same shape the Blade rails consume.
     * TV results use 'name' instead of 'title' and 'first_air_date' instead
     * of 'release_date', and genre IDs reference the TV genre list.
     *
     * @param  (callable(array, int): (string|null))|null  $badgeResolver
     */
    protected function mapTvResults(array $response, int $limit, ?callable $badgeResolver = null): array
    {
        $genres = $this->tvGenres();

        return collect($response['results'] ?? [])
            ->take($limit)
            ->values()
            ->map(fn (array $show, int $index) => [
                'title' => $show['name'] ?? 'Untitled',
                'image' => ! empty($show['backdrop_path'])
                    ? "{$this->imageBaseUrl}/w780{$show['backdrop_path']}"
                    : (! empty($show['poster_path']) ? "{$this->imageBaseUrl}/w500{$show['poster_path']}" : null),
                'year' => (! empty($show['first_air_date']))
                    ? substr($show['first_air_date'], 0, 4)
                    : 'TBA',
                'genre' => collect($show['genre_ids'] ?? [])
                    ->map(fn ($id) => $genres[$id] ?? null)
                    ->filter()
                    ->take(2)
                    ->implode(' · ') ?: 'Series',
                'score' => isset($show['vote_average']) ? (int) round($show['vote_average'] * 10) : null,
                'badge' => $badgeResolver ? $badgeResolver($show, $index) : null,
                'source' => 'tmdb',
                'tmdb_id' => $show['id'] ?? null,
                'type' => 'tv',
            ])
            ->filter(fn ($s) => $s['image'])
            ->values()
            ->all();
    }
}
