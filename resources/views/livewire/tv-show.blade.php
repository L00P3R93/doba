<div class="baze-tv-page">
    {{-- Back Navigation --}}
    <nav class="baze-watch-nav">
        <a href="{{ route('home') }}" class="baze-watch-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Back to Home
        </a>
    </nav>

    {{-- Show Hero --}}
    <div class="baze-tv-hero">
        @if (!empty($show['backdrop_path']))
            <div class="baze-tv-hero-bg">
                <img src="https://image.tmdb.org/t/p/w1280{{ $show['backdrop_path'] }}" alt="" loading="lazy" />
                <div class="baze-tv-hero-scrim"></div>
            </div>
        @endif

        <div class="baze-tv-hero-content">
            <h1 class="baze-tv-hero-title">{{ $show['name'] ?? 'Untitled' }}</h1>

            <div class="baze-watch-meta">
                @php
                    $startYear = !empty($show['first_air_date']) ? substr($show['first_air_date'], 0, 4) : null;
                    $endYear = !empty($show['last_air_date']) && $show['status'] !== 'Returning Series'
                        ? substr($show['last_air_date'], 0, 4)
                        : null;
                @endphp
                @if ($startYear)
                    <span>{{ $startYear }}{{ $endYear ? "–{$endYear}" : '' }}</span>
                @endif
                @if (!empty($show['genres']))
                    <span class="baze-watch-meta-dot">·</span>
                    <span>{{ collect($show['genres'])->pluck('name')->take(2)->implode(' · ') }}</span>
                @endif
                @if (!empty($show['number_of_seasons']))
                    <span class="baze-watch-meta-dot">·</span>
                    <span>{{ $show['number_of_seasons'] }} Season{{ $show['number_of_seasons'] > 1 ? 's' : '' }}</span>
                @endif
                @if (isset($show['vote_average']) && $show['vote_average'] > 0)
                    <span class="baze-watch-meta-dot">·</span>
                    <span class="baze-watch-score">{{ round($show['vote_average'] * 10) }}%</span>
                @endif
            </div>

            @if (!empty($show['overview']))
                <p class="baze-tv-hero-overview">{{ Str::limit($show['overview'], 280) }}</p>
            @endif
        </div>
    </div>

    {{-- Season Selector --}}
    @if (count($seasons) > 0)
        <div class="baze-tv-seasons">
            <div class="baze-tv-seasons-label">SEASONS</div>
            <div class="baze-tv-seasons-track">
                @foreach ($seasons as $season)
                    <button
                        type="button"
                        wire:click="loadSeason({{ $season['season_number'] }})"
                        class="baze-tv-season-btn {{ $selectedSeason === $season['season_number'] ? 'is-active' : '' }}"
                        aria-pressed="{{ $selectedSeason === $season['season_number'] ? 'true' : 'false' }}"
                    >
                        S{{ $season['season_number'] }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Episode List --}}
    <div class="baze-tv-episodes" wire:key="season-{{ $selectedSeason }}">
        @if (empty($episodes))
            <div class="baze-rail-empty">No episodes available for this season.</div>
        @else
            @foreach ($episodes as $ep)
                <a href="{{ route('watch.tv', [$tmdbId, $selectedSeason, $ep['episode_number']]) }}"
                   class="baze-tv-episode-card"
                   wire:key="ep-{{ $ep['episode_number'] }}">
                    <div class="baze-tv-episode-thumb">
                        @if (!empty($ep['still_path']))
                            <img src="https://image.tmdb.org/t/p/w300{{ $ep['still_path'] }}" alt="" loading="lazy" />
                        @else
                            <div class="baze-tv-episode-thumb-placeholder">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                            </div>
                        @endif
                        <div class="baze-tv-episode-play">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                        </div>
                    </div>

                    <div class="baze-tv-episode-info">
                        <div class="baze-tv-episode-num">{{ $ep['episode_number'] }}</div>
                        <div class="baze-tv-episode-details">
                            <div class="baze-tv-episode-title">{{ $ep['name'] ?? 'Untitled' }}</div>
                            <div class="baze-tv-episode-meta">
                                @if (!empty($ep['air_date']))
                                    <span>{{ \Carbon\Carbon::parse($ep['air_date'])->format('M j, Y') }}</span>
                                @endif
                                @if (!empty($ep['runtime']))
                                    <span class="baze-watch-meta-dot">·</span>
                                    <span>{{ $ep['runtime'] }}m</span>
                                @endif
                            </div>
                            @if (!empty($ep['overview']))
                                <p class="baze-tv-episode-overview">{{ \Illuminate\Support\Str::limit($ep['overview'], 150) }}</p>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        @endif
    </div>
</div>
