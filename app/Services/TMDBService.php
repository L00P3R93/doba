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
                        'watch_href' => '#trending-today',
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
                'badge' => $badgeResolver ? $badgeResolver($movie, $index) : null,
                'source' => 'tmdb',
                'tmdb_id' => $movie['id'] ?? null,
            ])
            ->filter(fn ($m) => $m['image']) // skip anything with no usable art
            ->values()
            ->all();
    }
}
