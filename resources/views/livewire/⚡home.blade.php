<div>
    {{-- ============================================================
         HERO — "Now Streaming" carousel
         ============================================================ --}}
    <section class="baze-stream-hero"
             x-data="heroCarousel({{ count($heroFeatures) }})"
             x-init="init()"
             @mouseenter="pause()" @mouseleave="resume()"
             @focusin="pause()" @focusout="resume()"
             @touchstart="touchX = $event.changedTouches[0].clientX"
             @touchend="Math.abs($event.changedTouches[0].clientX - touchX) > 40 && (($event.changedTouches[0].clientX - touchX) < 0 ? next() : prev())">

        <div class="baze-stream-hero-bg">
            @foreach ($heroFeatures as $index => $feature)
                <img src="{{ $feature['image'] }}"
                     alt="{{ $feature['title'] }}"
                     loading="eager"
                     fetchpriority="{{ $index === 0 ? 'high' : 'auto' }}"
                     decoding="async"
                     class="baze-stream-slide-img"
                     :class="{ 'is-active': active === {{ $index }} }">
            @endforeach
            <div class="baze-stream-hero-scrim"></div>
            <div class="baze-stream-hero-glow baze-stream-hero-glow--sun"></div>
            <div class="baze-stream-hero-glow baze-stream-hero-glow--freq"></div>
        </div>

        @if (count($heroFeatures) > 1)
            <button type="button" class="baze-stream-nav baze-stream-nav--prev" @click="prev()" aria-label="Previous title">‹</button>
            <button type="button" class="baze-stream-nav baze-stream-nav--next" @click="next()" aria-label="Next title">›</button>
        @endif

        <div class="baze-wrap baze-stream-hero-content">
            <div class="baze-stream-slides">
                @foreach ($heroFeatures as $index => $feature)
                    <div class="baze-stream-slide-copy"
                         x-show="active === {{ $index }}"
                         x-cloak
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 translate-y-3"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">

                        <span class="baze-stream-badge">{{ $feature['kicker'] }}</span>
                        <h1 class="baze-display baze-stream-title">{{ $feature['title'] }}</h1>
                        <p class="baze-stream-tagline">{{ $feature['tagline'] }}</p>

                        <div class="baze-stream-meta" aria-label="Title details">
                            @foreach ($feature['meta'] as $i => $item)
                                @if ($i > 0)<span class="baze-stream-meta-dot" aria-hidden="true">·</span>@endif
                                <span>{{ $item }}</span>
                            @endforeach
                        </div>

                        <div class="baze-stream-ctas" x-data="{ saved: false }">
                            <a href="{{ $feature['watch_href'] }}" class="baze-stream-btn-watch">
                                <span aria-hidden="true">▶</span> Watch Now
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <a href="{{ url('/pricing?mode=creator') }}" class="baze-stream-hero-subcta">Are you a filmmaker or artist? See Creator Plans →</a>

            @if (count($heroFeatures) > 1)
                <div class="baze-stream-progress" role="tablist" aria-label="Featured titles" style="margin-top: 12px !important;">
                    @foreach ($heroFeatures as $index => $feature)
                        <button type="button"
                                class="baze-stream-progress-seg"
                                role="tab"
                                :aria-selected="active === {{ $index }}"
                                aria-label="Show {{ $feature['title'] }}"
                                @click="goTo({{ $index }})">
                            <span class="baze-stream-progress-fill" :style="{ width: fillWidth({{ $index }}) }"></span>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        @if (count($heroFeatures) > 1)
            <div class="baze-stream-thumbrail" aria-hidden="true">
                @foreach ($heroFeatures as $index => $feature)
                    <button type="button"
                            class="baze-stream-thumb"
                            :class="{ 'is-active': active === {{ $index }} }"
                            @click="goTo({{ $index }})"
                            tabindex="-1">
                        <img src="{{ $feature['poster'] ?? $feature['image'] }}" alt="" loading="lazy">
                    </button>
                @endforeach
            </div>
        @endif
    </section>

    <div class="baze-marquee-strip" aria-hidden="true">
        <div class="baze-marquee-track">
            <div class="baze-marquee-set">
                <span>▶ MOVIES</span><span>♪ MUSIC</span><span>▤ SERIES</span><span>◉ TRAILERS</span><span>♪ ARTISTS</span><span>▲ EVENTS</span>
            </div>
            <div class="baze-marquee-set">
                <span>▶ MOVIES</span><span>♪ MUSIC</span><span>▤ SERIES</span><span>◉ TRAILERS</span><span>♪ ARTISTS</span><span>▲ EVENTS</span>
            </div>
            <div class="baze-marquee-set">
                <span>▶ MOVIES</span><span>♪ MUSIC</span><span>▤ SERIES</span><span>◉ TRAILERS</span><span>♪ ARTISTS</span><span>▲ EVENTS</span>
            </div>
        </div>
    </div>

    {{-- ============================================================
         CONTINUE WATCHING — landscape cards with progress
         ============================================================ --}}
    @if (count($continueWatching) > 0)
    <section class="baze-rail-section" id="continue-watching"
             x-data="{
                 init() {
                     const items = JSON.parse(localStorage.getItem('doba_continue_watching') || '[]');
                     if (items.length > 0) {
                         $wire.loadContinueWatching(items);
                     }
                 }
             }">
        <div class="baze-wrap">
            <div class="baze-rail-head">
                <div>
                    <div class="baze-eyebrow">PICK UP WHERE YOU LEFT OFF</div>
                    <h2 class="baze-display baze-h2">Continue Watching</h2>
                </div>
            </div>

            <div class="baze-rail-shell" x-data="baseRail('.baze-landscape-card')" x-init="init()">
                <button type="button" class="baze-rail-arrow baze-rail-arrow--prev" @click="scrollByCard(-1)" x-show="hasOverflow" :disabled="atStart" aria-label="Previous">‹</button>

                <div class="baze-rail-viewport">
                    <div class="baze-rail-track" x-ref="rail" role="region" aria-label="Continue watching" tabindex="0">
                        @foreach ($continueWatching as $item)
                            <a href="{{ $item['watch_href'] ?? route('home') }}" class="baze-landscape-card" style="text-decoration: none; color: inherit;">
                                <div class="baze-landscape-art">
                                    <img src="{{ asset('movies/images/'.$item['image']) }}" alt="{{ $item['title'] }}" loading="lazy">
                                    <div class="baze-landscape-scrim"></div>
                                    <div class="baze-landscape-playbtn" aria-hidden="true">▶</div>
                                </div>
                                <div class="baze-landscape-info">
                                    <div class="baze-poster-title">{{ $item['title'] }}</div>
                                    <div class="baze-poster-meta">{{ $item['remaining'] }}</div>
                                    <div class="baze-progress-track" role="progressbar" aria-valuenow="{{ $item['progress'] }}" aria-valuemin="0" aria-valuemax="100" aria-label="Watch progress for {{ $item['title'] }}">
                                        <div class="baze-progress-fill" style="width: {{ $item['progress'] }}%"></div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="baze-rail-fade baze-rail-fade--left" :class="{ 'is-visible': hasOverflow && !atStart }"></div>
                    <div class="baze-rail-fade baze-rail-fade--right" :class="{ 'is-visible': hasOverflow && !atEnd }"></div>
                </div>

                <button type="button" class="baze-rail-arrow baze-rail-arrow--next" @click="scrollByCard(1)" x-show="hasOverflow" :disabled="atEnd" aria-label="Next">›</button>
            </div>
        </div>
    </section>
    @endif

    {{-- ============================================================
         TRENDING TODAY — with Movies/TV toggle
         ============================================================ --}}
    <section class="baze-rail-section" id="trending-today">
        <div class="baze-wrap">
            <div class="baze-rail-head">
                <div>
                    <div class="baze-eyebrow">WHAT EVERYONE'S WATCHING RIGHT NOW</div>
                    <h2 class="baze-display baze-h2">Trending Today</h2>
                </div>
                <div class="baze-rail-toggle" role="tablist" aria-label="Trending Today content type" wire:target="switchRail" wire:loading.class="baze-toggle-loading">
                    <button class="baze-rail-toggle-btn {{ $trendingTodayType === 'movies' ? 'is-active' : '' }}"
                            wire:click="switchRail('trendingToday', 'movies')" role="tab"
                            aria-selected="{{ $trendingTodayType === 'movies' ? 'true' : 'false' }}">Movies</button>
                    <button class="baze-rail-toggle-btn {{ $trendingTodayType === 'tv' ? 'is-active' : '' }}"
                            wire:click="switchRail('trendingToday', 'tv')" role="tab"
                            aria-selected="{{ $trendingTodayType === 'tv' ? 'true' : 'false' }}">TV</button>
                </div>
            </div>

            <div class="baze-rail-shell" wire:key="trending-today-{{ $trendingTodayType }}" x-data="baseRail('.baze-poster-card')" x-init="init()">
                <button type="button" class="baze-rail-arrow baze-rail-arrow--prev" @click="scrollByCard(-1)" x-show="hasOverflow" :disabled="atStart" aria-label="Previous titles">‹</button>

                <div class="baze-rail-viewport">
                    {{-- Skeleton loading state — overlays the rail during crossfade --}}
                    <div wire:loading.class="baze-rail-skeleton--visible" wire:target="switchRail" class="baze-rail-skeleton">
                        @foreach (range(1, 6) as $skeleton)
                            <div class="baze-skeleton-card" style="width:280px;">
                                <div class="baze-skeleton-poster" style="aspect-ratio:16/9;border-radius:var(--radius-md);"></div>
                                <div class="baze-skeleton-text"></div>
                                <div class="baze-skeleton-text baze-skeleton-text--short"></div>
                            </div>
                        @endforeach
                    </div>
                    <div class="baze-rail-track" x-ref="rail" role="region" aria-label="Trending today" tabindex="0">
                        @php $items = $trendingTodayType === 'tv' ? $tvTrendingToday : $trendingToday; @endphp
                        @forelse ($items as $item)
                            @php
                                $cardHref = (!empty($item['type']) && $item['type'] === 'tv')
                                    ? route('tv.show', $item['tmdb_id'] ?? 0)
                                    : route('watch.movie', $item['tmdb_id'] ?? 0);
                            @endphp
                            <a href="{{ $cardHref }}" class="baze-poster-card" style="text-decoration: none; color: inherit;">
                                <div class="baze-poster-art">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" loading="lazy" onerror="this.style.display='none'">
                                    @if (!empty($item['score']))
                                        <span class="baze-score-badge {{ $item['score'] >= 70 ? 'baze-score-badge--high' : ($item['score'] >= 50 ? 'baze-score-badge--mid' : 'baze-score-badge--low') }}">{{ $item['score'] }}%</span>
                                    @endif
                                    <button type="button" class="baze-poster-add" aria-label="Add {{ $item['title'] }} to list">+</button>
                                    <div class="baze-poster-play" aria-hidden="true">▶</div>
                                </div>
                                <div class="baze-poster-info">
                                    <div class="baze-poster-title">{{ $item['title'] }}</div>
                                    <div class="baze-poster-meta">{{ $item['year'] }} · {{ $item['genre'] }}</div>
                                </div>
                            </a>
                        @empty
                            <div class="baze-rail-empty">No trending titles available right now.</div>
                        @endforelse
                    </div>
                    <div class="baze-rail-fade baze-rail-fade--left" :class="{ 'is-visible': hasOverflow && !atStart }"></div>
                    <div class="baze-rail-fade baze-rail-fade--right" :class="{ 'is-visible': hasOverflow && !atEnd }"></div>
                </div>

                <button type="button" class="baze-rail-arrow baze-rail-arrow--next" @click="scrollByCard(1)" x-show="hasOverflow" :disabled="atEnd" aria-label="Next titles">›</button>
            </div>
        </div>
    </section>

    {{-- ============================================================
         TRENDING THIS WEEK — with Movies/TV toggle
         ============================================================ --}}
    <section class="baze-rail-section" id="trending-week">
        <div class="baze-wrap">
            <div class="baze-rail-head">
                <div>
                    <div class="baze-eyebrow">THIS WEEK'S BIGGEST</div>
                    <h2 class="baze-display baze-h2">Trending This Week</h2>
                </div>
                <div class="baze-rail-toggle" role="tablist" aria-label="Trending This Week content type" wire:target="switchRail" wire:loading.class="baze-toggle-loading">
                    <button class="baze-rail-toggle-btn {{ $trendingWeekType === 'movies' ? 'is-active' : '' }}"
                            wire:click="switchRail('trendingWeek', 'movies')" role="tab"
                            aria-selected="{{ $trendingWeekType === 'movies' ? 'true' : 'false' }}">Movies</button>
                    <button class="baze-rail-toggle-btn {{ $trendingWeekType === 'tv' ? 'is-active' : '' }}"
                            wire:click="switchRail('trendingWeek', 'tv')" role="tab"
                            aria-selected="{{ $trendingWeekType === 'tv' ? 'true' : 'false' }}">TV</button>
                </div>
            </div>

            <div class="baze-rail-shell" wire:key="trending-week-{{ $trendingWeekType }}" x-data="baseRail('.baze-poster-card')" x-init="init()">
                <button type="button" class="baze-rail-arrow baze-rail-arrow--prev" @click="scrollByCard(-1)" x-show="hasOverflow" :disabled="atStart" aria-label="Previous titles">‹</button>

                <div class="baze-rail-viewport">
                    <div class="baze-rail-track" x-ref="rail" role="region" aria-label="Trending this week" tabindex="0">
                        @php $items = $trendingWeekType === 'tv' ? $tvTrendingWeek : $trendingWeek; @endphp
                        @forelse ($items as $item)
                            @php
                                $cardHref = (!empty($item['type']) && $item['type'] === 'tv')
                                    ? route('tv.show', $item['tmdb_id'] ?? 0)
                                    : route('watch.movie', $item['tmdb_id'] ?? 0);
                            @endphp
                            <a href="{{ $cardHref }}" class="baze-poster-card" style="text-decoration: none; color: inherit;">
                                <div class="baze-poster-art">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" loading="lazy" onerror="this.style.display='none'">
                                    @if (!empty($item['score']))
                                        <span class="baze-score-badge {{ $item['score'] >= 70 ? 'baze-score-badge--high' : ($item['score'] >= 50 ? 'baze-score-badge--mid' : 'baze-score-badge--low') }}">{{ $item['score'] }}%</span>
                                    @endif
                                    <button type="button" class="baze-poster-add" aria-label="Add {{ $item['title'] }} to list">+</button>
                                    <div class="baze-poster-play" aria-hidden="true">▶</div>
                                </div>
                                <div class="baze-poster-info">
                                    <div class="baze-poster-title">{{ $item['title'] }}</div>
                                    <div class="baze-poster-meta">{{ $item['year'] }} · {{ $item['genre'] }}</div>
                                </div>
                            </a>
                        @empty
                            <div class="baze-rail-empty">No trending titles available right now.</div>
                        @endforelse
                    </div>
                    <div class="baze-rail-fade baze-rail-fade--left" :class="{ 'is-visible': hasOverflow && !atStart }"></div>
                    <div class="baze-rail-fade baze-rail-fade--right" :class="{ 'is-visible': hasOverflow && !atEnd }"></div>
                </div>

                <button type="button" class="baze-rail-arrow baze-rail-arrow--next" @click="scrollByCard(1)" x-show="hasOverflow" :disabled="atEnd" aria-label="Next titles">›</button>
            </div>
        </div>
    </section>

    {{-- ============================================================
         NEW EPISODES — TV-only rail, no toggle
         ============================================================ --}}
    <section class="baze-rail-section" id="new-episodes">
        <div class="baze-wrap">
            <div class="baze-rail-head">
                <div>
                    <div class="baze-eyebrow">FRESH EPISODES DROPPING NOW</div>
                    <h2 class="baze-display baze-h2">New Episodes</h2>
                </div>
            </div>

            <div class="baze-rail-shell" x-data="baseRail('.baze-landscape-card')" x-init="init()">
                <button type="button" class="baze-rail-arrow baze-rail-arrow--prev" @click="scrollByCard(-1)" x-show="hasOverflow" :disabled="atStart" aria-label="Previous episodes">‹</button>

                <div class="baze-rail-viewport">
                    <div class="baze-rail-track" x-ref="rail" role="region" aria-label="New episodes" tabindex="0">
                        @forelse ($newEpisodes as $item)
                            <a href="{{ route('watch.tv', [$item['tmdb_id'] ?? 0, 1, 1]) }}"
                               class="baze-landscape-card"
                               style="text-decoration: none; color: inherit;">
                                <div class="baze-landscape-art">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" loading="lazy">
                                    @if (!empty($item['score']))
                                        <span class="baze-score-badge {{ $item['score'] >= 70 ? 'baze-score-badge--high' : ($item['score'] >= 50 ? 'baze-score-badge--mid' : 'baze-score-badge--low') }}">{{ $item['score'] }}%</span>
                                    @endif
                                    <div class="baze-landscape-scrim"></div>
                                    <div class="baze-landscape-playbtn" aria-hidden="true">▶</div>
                                </div>
                                <div class="baze-landscape-info">
                                    <div class="baze-poster-title">{{ $item['title'] }}</div>
                                    <div class="baze-poster-meta">{{ $item['year'] }} · {{ $item['genre'] }}</div>
                                </div>
                            </a>
                        @empty
                            <div class="baze-rail-empty">No new episodes available right now.</div>
                        @endforelse
                    </div>
                    <div class="baze-rail-fade baze-rail-fade--left" :class="{ 'is-visible': hasOverflow && !atStart }"></div>
                    <div class="baze-rail-fade baze-rail-fade--right" :class="{ 'is-visible': hasOverflow && !atEnd }"></div>
                </div>

                <button type="button" class="baze-rail-arrow baze-rail-arrow--next" @click="scrollByCard(1)" x-show="hasOverflow" :disabled="atEnd" aria-label="Next episodes">›</button>
            </div>
        </div>
    </section>

    {{-- ============================================================
         TOP RATED — with Movies/TV toggle
         ============================================================ --}}
    <section class="baze-rail-section" id="top-rated">
        <div class="baze-wrap">
            <div class="baze-rail-head">
                <div>
                    <div class="baze-eyebrow">CRITICALLY ACCLAIMED</div>
                    <h2 class="baze-display baze-h2">Top Rated</h2>
                </div>
                <div class="baze-rail-toggle" role="tablist" aria-label="Top Rated content type" wire:target="switchRail" wire:loading.class="baze-toggle-loading">
                    <button class="baze-rail-toggle-btn {{ $topRatedType === 'movies' ? 'is-active' : '' }}"
                            wire:click="switchRail('topRated', 'movies')" role="tab"
                            aria-selected="{{ $topRatedType === 'movies' ? 'true' : 'false' }}">Movies</button>
                    <button class="baze-rail-toggle-btn {{ $topRatedType === 'tv' ? 'is-active' : '' }}"
                            wire:click="switchRail('topRated', 'tv')" role="tab"
                            aria-selected="{{ $topRatedType === 'tv' ? 'true' : 'false' }}">TV</button>
                </div>
            </div>

            <div class="baze-rail-shell" wire:key="top-rated-{{ $topRatedType }}" x-data="baseRail('.baze-poster-card')" x-init="init()">
                <button type="button" class="baze-rail-arrow baze-rail-arrow--prev" @click="scrollByCard(-1)" x-show="hasOverflow" :disabled="atStart" aria-label="Previous titles">‹</button>

                <div class="baze-rail-viewport">
                    <div class="baze-rail-track" x-ref="rail" role="region" aria-label="Top rated" tabindex="0">
                        @php $items = $topRatedType === 'tv' ? $tvTopRated : $topRatedMovies; @endphp
                        @forelse ($items as $item)
                            @php
                                $cardHref = (!empty($item['type']) && $item['type'] === 'tv')
                                    ? route('tv.show', $item['tmdb_id'] ?? 0)
                                    : route('watch.movie', $item['tmdb_id'] ?? 0);
                            @endphp
                            <a href="{{ $cardHref }}" class="baze-poster-card" style="text-decoration: none; color: inherit;">
                                <div class="baze-poster-art">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" loading="lazy" onerror="this.style.display='none'">
                                    @if (!empty($item['score']))
                                        <span class="baze-score-badge {{ $item['score'] >= 70 ? 'baze-score-badge--high' : ($item['score'] >= 50 ? 'baze-score-badge--mid' : 'baze-score-badge--low') }}">{{ $item['score'] }}%</span>
                                    @endif
                                    <button type="button" class="baze-poster-add" aria-label="Add {{ $item['title'] }} to list">+</button>
                                    <div class="baze-poster-play" aria-hidden="true">▶</div>
                                </div>
                                <div class="baze-poster-info">
                                    <div class="baze-poster-title">{{ $item['title'] }}</div>
                                    <div class="baze-poster-meta">{{ $item['year'] }} · {{ $item['genre'] }}</div>
                                </div>
                            </a>
                        @empty
                            <div class="baze-rail-empty">No top rated titles available right now.</div>
                        @endforelse
                    </div>
                    <div class="baze-rail-fade baze-rail-fade--left" :class="{ 'is-visible': hasOverflow && !atStart }"></div>
                    <div class="baze-rail-fade baze-rail-fade--right" :class="{ 'is-visible': hasOverflow && !atEnd }"></div>
                </div>

                <button type="button" class="baze-rail-arrow baze-rail-arrow--next" @click="scrollByCard(1)" x-show="hasOverflow" :disabled="atEnd" aria-label="Next titles">›</button>
            </div>
        </div>
    </section>

    {{-- ============================================================
         POPULAR — with Movies/TV toggle
         ============================================================ --}}
    <section class="baze-rail-section" id="popular">
        <div class="baze-wrap">
            <div class="baze-rail-head">
                <div>
                    <div class="baze-eyebrow">CROWD FAVOURITES</div>
                    <h2 class="baze-display baze-h2">Popular</h2>
                </div>
                <div class="baze-rail-toggle" role="tablist" aria-label="Popular content type" wire:target="switchRail" wire:loading.class="baze-toggle-loading">
                    <button class="baze-rail-toggle-btn {{ $popularType === 'movies' ? 'is-active' : '' }}"
                            wire:click="switchRail('popular', 'movies')" role="tab"
                            aria-selected="{{ $popularType === 'movies' ? 'true' : 'false' }}">Movies</button>
                    <button class="baze-rail-toggle-btn {{ $popularType === 'tv' ? 'is-active' : '' }}"
                            wire:click="switchRail('popular', 'tv')" role="tab"
                            aria-selected="{{ $popularType === 'tv' ? 'true' : 'false' }}">TV</button>
                </div>
            </div>

            <div class="baze-rail-shell" wire:key="popular-{{ $popularType }}" x-data="baseRail('.baze-poster-card')" x-init="init()">
                <button type="button" class="baze-rail-arrow baze-rail-arrow--prev" @click="scrollByCard(-1)" x-show="hasOverflow" :disabled="atStart" aria-label="Previous titles">‹</button>

                <div class="baze-rail-viewport">
                    <div class="baze-rail-track" x-ref="rail" role="region" aria-label="Popular" tabindex="0">
                        @php $items = $popularType === 'tv' ? $tvPopular : $popularMovies; @endphp
                        @forelse ($items as $item)
                            @php
                                $cardHref = (!empty($item['type']) && $item['type'] === 'tv')
                                    ? route('tv.show', $item['tmdb_id'] ?? 0)
                                    : route('watch.movie', $item['tmdb_id'] ?? 0);
                            @endphp
                            <a href="{{ $cardHref }}" class="baze-poster-card" style="text-decoration: none; color: inherit;">
                                <div class="baze-poster-art">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" loading="lazy" onerror="this.style.display='none'">
                                    @if (!empty($item['score']))
                                        <span class="baze-score-badge {{ $item['score'] >= 70 ? 'baze-score-badge--high' : ($item['score'] >= 50 ? 'baze-score-badge--mid' : 'baze-score-badge--low') }}">{{ $item['score'] }}%</span>
                                    @endif
                                    <button type="button" class="baze-poster-add" aria-label="Add {{ $item['title'] }} to list">+</button>
                                    <div class="baze-poster-play" aria-hidden="true">▶</div>
                                </div>
                                <div class="baze-poster-info">
                                    <div class="baze-poster-title">{{ $item['title'] }}</div>
                                    <div class="baze-poster-meta">{{ $item['year'] }} · {{ $item['genre'] }}</div>
                                </div>
                            </a>
                        @empty
                            <div class="baze-rail-empty">No popular titles available right now.</div>
                        @endforelse
                    </div>
                    <div class="baze-rail-fade baze-rail-fade--left" :class="{ 'is-visible': hasOverflow && !atStart }"></div>
                    <div class="baze-rail-fade baze-rail-fade--right" :class="{ 'is-visible': hasOverflow && !atEnd }"></div>
                </div>

                <button type="button" class="baze-rail-arrow baze-rail-arrow--next" @click="scrollByCard(1)" x-show="hasOverflow" :disabled="atEnd" aria-label="Next titles">›</button>
            </div>
        </div>
    </section>

    {{-- ============================================================
         AFRICAN STORIES — creator spotlight
         ============================================================ --}}
    <section class="baze-section baze-section--tight" id="creators">
        <div class="baze-wrap">
            <div class="baze-spotlight">
                <div class="baze-spotlight-copy">
                    <div class="baze-eyebrow baze-eyebrow--mustard">FROM OUR CREATORS</div>
                    <h3 class="baze-display baze-h3">STORIES MADE HERE. SHARED EVERYWHERE.</h3>
                    <p>
                        DobaPlay isn't just where you watch — it's where independent filmmakers, studios,
                        record labels, and artists across East Africa publish their own work. The Cinema plan
                        brings full VOD distribution to filmmakers: set your own rental or pay-per-view pricing,
                        ship subtitles in multiple languages, and promote your premiere to a built-in audience.
                    </p>
                    <div class="baze-spotlight-tags">
                        <span class="baze-tag">Rental &amp; PPV pricing</span>
                        <span class="baze-tag">Multi-language subtitles</span>
                        <span class="baze-tag">Premiere promotion</span>
                    </div>
                    <a href="{{ url('/pricing?mode=creator') }}" class="baze-btn baze-btn-primary" style="background:var(--color-mustard)">
                        Distribute your film
                    </a>
                </div>
                <div class="baze-spotlight-visual">
                    <img class="baze-reel" src="{{ asset('home/cinema-2.png') }}" alt="Film reel and popcorn">
                    <img class="baze-clap" src="{{ asset('home/cinema-1.png') }}" alt="Clapperboard">
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         LISTENER STRIP
         ============================================================ --}}
    <section class="baze-section baze-section--tight" id="listeners">
        <div class="baze-wrap">
            <div class="baze-listener-strip">
                <div>
                    <h4 class="baze-display">JUST HERE TO LISTEN?</h4>
                    <p>Go premium for offline downloads, zero ads, and early access to new releases from your favourite East African artists.</p>
                </div>
                <a href="{{ url('/pricing?mode=listener') }}" class="baze-btn baze-btn-outline-freq">See listener plans →</a>
            </div>
        </div>
    </section>

    @once
        <script>
            function baseRail(cardSelector = '.baze-rail-card') {
                return {
                    atStart: true,
                    atEnd: false,
                    hasOverflow: false,
                    showHint: true,
                    init() {
                        this.measure();
                        this.$refs.rail.addEventListener('scroll', () => this.onScroll(), { passive: true });
                        if (window.ResizeObserver) {
                            new ResizeObserver(() => this.measure()).observe(this.$refs.rail);
                        }
                        window.addEventListener('resize', () => this.measure());
                    },
                    measure() {
                        const rail = this.$refs.rail;
                        this.hasOverflow = rail.scrollWidth > rail.clientWidth + 4;
                        this.onScroll();
                    },
                    onScroll() {
                        const rail = this.$refs.rail;
                        const max = rail.scrollWidth - rail.clientWidth;
                        this.atStart = rail.scrollLeft <= 4;
                        this.atEnd = max <= 4 || rail.scrollLeft >= max - 4;
                        if (rail.scrollLeft > 4) this.showHint = false;
                    },
                    scrollByCard(direction) {
                        const rail = this.$refs.rail;
                        const card = rail.querySelector(cardSelector);
                        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                        const step = card ? card.getBoundingClientRect().width + 16 : rail.clientWidth * 0.85;
                        rail.scrollBy({ left: direction * step, behavior: reduceMotion ? 'auto' : 'smooth' });
                    }
                }
            }

            function heroCarousel(count, intervalMs = 7000) {
                return {
                    active: 0,
                    count: count,
                    progress: 0,
                    paused: false,
                    reduceMotion: false,
                    touchX: 0,
                    timer: null,
                    init() {
                        this.reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                        if (this.count > 1 && !this.reduceMotion) this.start();
                    },
                    start() {
                        clearInterval(this.timer);
                        this.progress = 0;
                        const tickMs = 100;
                        this.timer = setInterval(() => {
                            if (this.paused) return;
                            this.progress += (tickMs / intervalMs) * 100;
                            if (this.progress >= 100) this.next();
                        }, tickMs);
                    },
                    stop() {
                        clearInterval(this.timer);
                    },
                    goTo(i) {
                        this.active = i;
                        this.progress = 0;
                        if (!this.reduceMotion) this.start(); else this.stop();
                    },
                    next() {
                        this.active = (this.active + 1) % this.count;
                        this.progress = 0;
                    },
                    prev() {
                        this.active = (this.active - 1 + this.count) % this.count;
                        this.progress = 0;
                        if (!this.reduceMotion) this.start();
                    },
                    pause() { this.paused = true; },
                    resume() { this.paused = false; },
                    fillWidth(i) {
                        if (i < this.active) return '100%';
                        if (i > this.active) return '0%';
                        return (this.reduceMotion ? 100 : this.progress) + '%';
                    },
                }
            }
        </script>
    @endonce
</div>
