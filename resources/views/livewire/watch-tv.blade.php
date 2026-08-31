<div class="baze-watch-page">
    {{-- Back Navigation --}}
    <nav class="baze-watch-nav">
        <a href="{{ route('tv.show', $tmdbId) }}" class="baze-watch-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Back to {{ $show['name'] ?? 'Show' }}
        </a>
    </nav>

    {{-- Player --}}
    @if (!empty($embedUrl))
        <div class="baze-player-container">
            <iframe
                src="{{ $embedUrl }}"
                title="{{ $show['name'] ?? 'Episode' }} S{{ sprintf('%02d', $season) }}E{{ sprintf('%02d', $episode) }}"
                allowfullscreen
                allow="autoplay; encrypted-media"
                loading="lazy"
            ></iframe>
        </div>
    @else
        <div class="baze-player-container baze-player-empty">
            <div class="baze-player-empty-inner">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-bone-muted);"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8" fill="currentColor" stroke="none"/></svg>
                <p>Player unavailable for this episode.</p>
            </div>
        </div>
    @endif

    {{-- Provider Selector --}}
    @if (count($providers) > 1)
        <div class="baze-watch-providers">
            <span class="baze-watch-providers-label">SERVERS</span>
            <div class="baze-watch-providers-grid">
                @foreach ($providers as $provider)
                    <button
                        type="button"
                        wire:click="switchProvider('{{ $provider['key'] }}')"
                        class="baze-watch-server-btn {{ $activeProvider === $provider['key'] ? 'is-active' : '' }}"
                        aria-pressed="{{ $activeProvider === $provider['key'] ? 'true' : 'false' }}"
                    >
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>
                        {{ $provider['name'] }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Episode Info --}}
    <div class="baze-watch-info">
        <div class="baze-watch-info-main">
            <div class="baze-tv-episode-label">S{{ sprintf('%02d', $season) }} · E{{ sprintf('%02d', $episode) }}</div>
            <h1 class="baze-watch-title">{{ $episodeData['name'] ?? $show['name'] ?? 'Untitled' }}</h1>

            <div class="baze-watch-meta">
                <span>{{ $show['name'] ?? '' }}</span>
                @if (!empty($episodeData['air_date']))
                    <span class="baze-watch-meta-dot">·</span>
                    <span>{{ \Carbon\Carbon::parse($episodeData['air_date'])->format('M j, Y') }}</span>
                @endif
                @if (!empty($episodeData['runtime']))
                    <span class="baze-watch-meta-dot">·</span>
                    <span>{{ $episodeData['runtime'] }}m</span>
                @endif
            </div>

            @if (!empty($episodeData['overview']))
                <p class="baze-watch-overview">{{ $episodeData['overview'] }}</p>
            @endif
        </div>

        {{-- Action Buttons --}}
        <div class="baze-watch-actions">
            <button type="button" class="baze-watch-action-btn" wire:click="$toggle('saved')" aria-pressed="{{ $saved ? 'true' : 'false' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
                Save
            </button>
            <button type="button" class="baze-watch-action-btn" onclick="navigator.share?.({ title: '{{ addslashes($show['name'] ?? '') }} S{{ sprintf('%02d', $season) }}E{{ sprintf('%02d', $episode) }}', url: window.location.href })">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                Share
            </button>
        </div>
    </div>

    {{-- Episode Navigation --}}
    <div class="baze-tv-nav">
        <button
            type="button"
            wire:click="prevEpisode"
            class="baze-tv-nav-btn {{ $episode <= 1 ? 'is-disabled' : '' }}"
            {{ $episode <= 1 ? 'disabled' : '' }}
        >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Previous
        </button>

        <span class="baze-tv-nav-label">E{{ $episode }} of {{ $totalEpisodes }}</span>

        <button
            type="button"
            wire:click="nextEpisode"
            class="baze-tv-nav-btn {{ $episode >= $totalEpisodes ? 'is-disabled' : '' }}"
            {{ $episode >= $totalEpisodes ? 'disabled' : '' }}
        >
            Next
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
        </button>
    </div>
</div>
