<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class StreamingProviderService
{
    /**
     * Get the embed URL for a movie.
     *
     * @param  int  $tmdbId  TMDB movie ID
     * @param  string|null  $imdbId  IMDb ID (e.g. "tt1234567") — used if the provider prefers it
     * @param  string|null  $provider  Override the default provider
     */
    public function getMovieUrl(int $tmdbId, ?string $imdbId = null, ?string $provider = null): ?string
    {
        $config = $this->resolveProvider($provider);

        if ($config === null) {
            return null;
        }

        $id = $imdbId ?? $tmdbId;
        $path = str_replace('{id}', (string) $id, $config['movie_path']);

        return $this->buildUrl($config, $path);
    }

    /**
     * Get the embed URL for a TV show episode.
     *
     * @param  int  $tmdbId  TMDB TV show ID
     * @param  int  $season  Season number
     * @param  int  $episode  Episode number
     * @param  string|null  $imdbId  IMDb ID — used if the provider prefers it
     * @param  string|null  $provider  Override the default provider
     */
    public function getTvUrl(int $tmdbId, int $season, int $episode, ?string $imdbId = null, ?string $provider = null): ?string
    {
        $config = $this->resolveProvider($provider);

        if ($config === null) {
            return null;
        }

        $id = $imdbId ?? $tmdbId;
        $path = str_replace(
            ['{id}', '{season}', '{episode}', '{s}', '{e}'],
            [(string) $id, (string) $season, (string) $episode, (string) $season, (string) $episode],
            $config['tv_path']
        );

        return $this->buildUrl($config, $path);
    }

    /**
     * Get all configured providers.
     *
     * @return array<string, array{name: string, base_url: string}>
     */
    public function getProviders(): array
    {
        return collect(config('streaming.providers', []))
            ->map(fn (array $provider) => [
                'name' => $provider['name'],
                'base_url' => $provider['base_url'],
            ])
            ->all();
    }

    /**
     * Get a specific provider's configuration.
     */
    public function getProvider(string $name): ?array
    {
        return config("streaming.providers.{$name}");
    }

    /**
     * Get the primary provider name from config.
     */
    public function getPrimaryProvider(): string
    {
        return config('streaming.primary', 'vixsrc');
    }

    /**
     * Resolve the provider config, falling back to primary if the requested one is invalid.
     */
    protected function resolveProvider(?string $provider): ?array
    {
        $name = $provider ?? $this->getPrimaryProvider();
        $config = $this->getProvider($name);

        if ($config === null) {
            Log::warning('Streaming provider not found', ['provider' => $name]);

            return null;
        }

        return $config;
    }

    /**
     * Build the full embed URL from provider config and path.
     */
    protected function buildUrl(array $config, string $path): string
    {
        $baseUrl = rtrim($config['base_url'], '/');
        $path = '/'.ltrim($path, '/');
        $params = $config['params'] ?? [];

        $url = $baseUrl.$path;

        if (! empty($params)) {
            $url .= '?'.http_build_query($params);
        }

        return $url;
    }
}
