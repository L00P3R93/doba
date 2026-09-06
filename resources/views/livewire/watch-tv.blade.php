<div class="baze-watch-page"
     x-data="{
         panelOpen: false,
         episodeView: 'grid',
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
                         image: @js(!empty($show['backdrop_path']) ? "https://image.tmdb.org/t/p/w780{$show['backdrop_path']}" : (!empty($show['poster_path']) ? "https://image.tmdb.org/t/p/w500{$show['poster_path']}" : '')),
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

    {{-- Main Layout: Player + Sidebar --}}
    <div class="baze-watch-layout">
        {{-- Player Column --}}
        <div class="baze-watch-player-col">
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
            </div>

            {{-- Mobile: Panel Toggle Button --}}
            <button type="button" class="baze-watch-panel-toggle" @click="panelOpen = !panelOpen" :aria-expanded="panelOpen">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>
                <span>Servers & Episodes</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" :style="panelOpen ? 'transform:rotate(180deg)' : ''" style="transition:transform .2s ease"><path d="m6 9 6 6 6-6"/></svg>
            </button>
        </div>

        {{-- Sidebar Panel --}}
        <aside class="baze-watch-sidebar" :class="{ 'is-open': panelOpen }">
            {{-- Servers --}}
            @if (count($providers) > 1)
                <div class="baze-sidebar-section">
                    <div class="baze-sidebar-label">SERVERS</div>
                    <div class="baze-sidebar-servers">
                        @foreach ($providers as $provider)
                            <button
                                type="button"
                                wire:click="switchProvider('{{ $provider['key'] }}')"
                                class="baze-server-pill {{ $activeProvider === $provider['key'] ? 'is-active' : '' }}"
                                aria-pressed="{{ $activeProvider === $provider['key'] ? 'true' : 'false' }}"
                            >
                                <span>P{{ $loop->index + 1 }}</span>
                                @if ($activeProvider === $provider['key'])
                                    <span class="baze-server-pill-status">
                                        <span class="baze-server-pill-dot"></span>
                                        ON
                                    </span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Seasons --}}
            @if (count($seasons) > 0)
                <div class="baze-sidebar-section">
                    <div class="baze-sidebar-label">
                        <span>SEASONS</span>
                        <span class="baze-sidebar-label-meta">{{ $totalEpisodes }} eps</span>
                    </div>
                    <div class="baze-sidebar-seasons">
                        @foreach ($seasons as $s)
                            <button
                                type="button"
                                wire:click="switchSeason({{ $s['season_number'] }})"
                                class="baze-season-pill {{ $season === $s['season_number'] ? 'is-active' : '' }}"
                            >
                                S{{ sprintf('%02d', $s['season_number']) }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Episodes --}}
            <div class="baze-sidebar-section">
                <div class="baze-sidebar-label">
                    <span>EPISODES</span>
                    <div class="baze-episode-view-toggle">
                        <button type="button" class="baze-view-btn" :class="{ 'is-active': episodeView === 'grid' }" @click="episodeView = 'grid'" aria-label="Grid view">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        </button>
                        <button type="button" class="baze-view-btn" :class="{ 'is-active': episodeView === 'list' }" @click="episodeView = 'list'" aria-label="List view">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Grid View --}}
                <div class="baze-episode-grid" x-show="episodeView === 'grid'" x-cloak>
                    @forelse ($episodes as $ep)
                        <button
                            type="button"
                            wire:click="goToEpisode({{ $season }}, {{ $ep['episode_number'] }})"
                            class="baze-episode-num-btn {{ $ep['episode_number'] === $episode ? 'is-active' : '' }}"
                        >
                            {{ $ep['episode_number'] }}
                        </button>
                    @empty
                        <div class="baze-rail-empty">No episodes.</div>
                    @endforelse
                </div>

                {{-- List View --}}
                <div class="baze-episode-list" x-show="episodeView === 'list'" x-cloak>
                    @forelse ($episodes as $ep)
                        @php
                            $isActive = $ep['episode_number'] === $episode;
                            $thumb = $ep['still_path'] ?? $show['backdrop_path'] ?? $show['poster_path'] ?? '';
                            $thumbUrl = $thumb ? "https://image.tmdb.org/t/p/w300{$thumb}" : '';
                        @endphp
                        <button
                            type="button"
                            wire:click="goToEpisode({{ $season }}, {{ $ep['episode_number'] }})"
                            class="baze-episode-list-card {{ $isActive ? 'is-active' : '' }}"
                        >
                            <div class="baze-episode-list-thumb">
                                @if ($thumbUrl)
                                    <img src="{{ $thumbUrl }}" alt="" loading="lazy" onerror="this.style.display='none'">
                                @else
                                    <div class="baze-episode-list-placeholder">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                    </div>
                                @endif
                                <span class="baze-episode-list-badge {{ $isActive ? 'is-active' : '' }}">E{{ $ep['episode_number'] }}</span>
                            </div>
                            <div class="baze-episode-list-info">
                                <div class="baze-episode-list-title">{{ $ep['name'] ?? 'Episode ' . $ep['episode_number'] }}</div>
                                @if (!empty($ep['air_date']))
                                    <div class="baze-episode-list-date">{{ \Carbon\Carbon::parse($ep['air_date'])->format('M j, Y') }}</div>
                                @endif
                            </div>
                        </button>
                    @empty
                        <div class="baze-rail-empty">No episodes.</div>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>
</div>
