<div class="baze-watch-page"
     x-data="{
         init() {
             this.$el._startTime = Date.now();
             const timer = setInterval(() => {
                 const iframe = document.querySelector('.baze-player-container iframe');
                 if (iframe) {
                     const elapsed = (Date.now() - this.$el._startTime) / 1000;
                     const progress = Math.min(95, Math.round((elapsed / 2700) * 100));
                     saveWatchProgress({
                         tmdbId: {{ $tmdbId }},
                         type: 'tv',
                         title: '{{ addslashes($show['name'] ?? '') }}',
                         image: '{{ addslashes($show['image'] ?? '') }}',
                         season: {{ $season }},
                         episode: {{ $episode }},
                         progress: progress,
                     });
                 }
             }, 30000);
             this.$once('destroy', () => clearInterval(timer));
         }
     }">
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

    {{-- Controls Bar: Servers | Episode Nav | Browse --}}
    <div class="baze-watch-controls">
        {{-- Server Selector --}}
        @if (count($providers) > 1)
            <div class="baze-watch-controls-servers">
                @foreach ($providers as $provider)
                    <button
                        type="button"
                        wire:click="switchProvider('{{ $provider['key'] }}')"
                        class="baze-watch-server-btn {{ $activeProvider === $provider['key'] ? 'is-active' : '' }}"
                        aria-pressed="{{ $activeProvider === $provider['key'] ? 'true' : 'false' }}"
                    >
                        {{ 'S' . ($loop->index + 1) }}
                    </button>
                @endforeach
            </div>
        @endif

        <div class="baze-watch-controls-right">
            {{-- Episode Navigation --}}
            <div class="baze-watch-controls-epnav">
                <button
                    type="button"
                    wire:click="prevEpisode"
                    class="baze-watch-ctrl-btn"
                    {{ $episode <= 1 ? 'disabled' : '' }}
                    aria-label="Previous episode"
                >
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <span class="baze-watch-ctrl-label">E{{ $episode }}/{{ $totalEpisodes }}</span>
                <button
                    type="button"
                    wire:click="nextEpisode"
                    class="baze-watch-ctrl-btn"
                    {{ $episode >= $totalEpisodes ? 'disabled' : '' }}
                    aria-label="Next episode"
                >
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>

            {{-- Browse Seasons Toggle --}}
            @if (count($seasons) > 0)
                <div x-data="{ open: false }">
                    <button type="button" class="baze-watch-ctrl-btn baze-watch-ctrl-browse" @click="open = !open" :aria-expanded="open">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>
                        <span class="baze-watch-ctrl-browse-text">Episodes</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" :style="open ? 'transform:rotate(180deg)' : ''" style="transition:transform .2s ease"><path d="m6 9 6 6 6-6"/></svg>
                    </button>

                    {{-- Episodes Panel (positioned below controls) --}}
                    <div class="baze-watch-episodes-dropdown" x-show="open" x-transition.opacity.duration.200ms x-cloak @click.outside="open = false">
                        {{-- Season Selector --}}
                        <div class="baze-tv-seasons">
                            <div class="baze-tv-seasons-track">
                                @foreach ($seasons as $s)
                                    <button
                                        type="button"
                                        wire:click="switchSeason({{ $s['season_number'] }})"
                                        class="baze-tv-season-btn {{ $season === $s['season_number'] ? 'is-active' : '' }}"
                                    >
                                        S{{ sprintf('%02d', $s['season_number']) }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Episode List --}}
                        <div class="baze-tv-episodes">
                            @forelse ($episodes as $ep)
                                @php
                                    $isActive = $ep['episode_number'] === $episode;
                                    $thumb = $ep['still_path'] ?? $show['backdrop_path'] ?? $show['poster_path'] ?? '';
                                    $thumbUrl = $thumb ? "https://image.tmdb.org/t/p/w300{$thumb}" : '';
                                @endphp
                                <button
                                    type="button"
                                    wire:click="goToEpisode({{ $season }}, {{ $ep['episode_number'] }}); $dispatch('close')"
                                    class="baze-tv-episode-card {{ $isActive ? 'is-active' : '' }}"
                                >
                                    <div class="baze-tv-episode-thumb">
                                        @if ($thumbUrl)
                                            <img src="{{ $thumbUrl }}" alt="" loading="lazy" onerror="this.style.display='none'">
                                        @else
                                            <div class="baze-tv-episode-thumb-placeholder">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                            </div>
                                        @endif
                                        <div class="baze-tv-episode-play">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3" fill="currentColor" stroke="none"/></svg>
                                        </div>
                                    </div>
                                    <div class="baze-tv-episode-info">
                                        <span class="baze-tv-episode-num">{{ $ep['episode_number'] }}</span>
                                        <div class="baze-tv-episode-details">
                                            <div class="baze-tv-episode-title">{{ $ep['name'] ?? 'Episode ' . $ep['episode_number'] }}</div>
                                            <div class="baze-tv-episode-meta">
                                                @if (!empty($ep['runtime']))
                                                    <span>{{ $ep['runtime'] }}m</span>
                                                @endif
                                                @if (!empty($ep['air_date']))
                                                    <span>{{ \Carbon\Carbon::parse($ep['air_date'])->format('M j, Y') }}</span>
                                                @endif
                                            </div>
                                            @if (!empty($ep['overview']))
                                                <p class="baze-tv-episode-overview">{{ $ep['overview'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </button>
                            @empty
                                <div class="baze-rail-empty">No episodes available for this season.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

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
</div>
