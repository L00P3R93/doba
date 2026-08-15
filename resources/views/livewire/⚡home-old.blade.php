<div>
    {{-- ============================================================
         HERO — "Now Streaming"
         Cinematic featured-title hero. Background art crops via
         object-fit so it survives either portrait or landscape
         source assets; verify the crop against the real file and
         swap $heroFeature['image'] if a wider still frames better.
         ============================================================ --}}
    <section class="baze-stream-hero" x-data="{ saved: false }">
        <div class="baze-stream-hero-bg">
            <img src="{{ asset('movies/images/'.$heroFeature['image']) }}"
                 alt="{{ $heroFeature['title'] }}"
                 loading="eager"
                 fetchpriority="high">
            <div class="baze-stream-hero-scrim"></div>
            <div class="baze-stream-hero-glow baze-stream-hero-glow--sun"></div>
            <div class="baze-stream-hero-glow baze-stream-hero-glow--freq"></div>
        </div>

        <div class="baze-wrap baze-stream-hero-content">
            <span class="baze-stream-badge">{{ $heroFeature['kicker'] }}</span>
            <h1 class="baze-display baze-stream-title">{{ $heroFeature['title'] }}</h1>
            <p class="baze-stream-tagline">{{ $heroFeature['tagline'] }}</p>

            <div class="baze-stream-meta" aria-label="Title details">
                @foreach ($heroFeature['meta'] as $i => $item)
                    @if ($i > 0)<span class="baze-stream-meta-dot" aria-hidden="true">·</span>@endif
                    <span>{{ $item }}</span>
                @endforeach
            </div>

            <div class="baze-stream-ctas">
                <a href="{{ $heroFeature['watch_href'] }}" class="baze-stream-btn-watch">
                    <span aria-hidden="true">▶</span> Watch Now
                </a>
                <button type="button"
                        class="baze-stream-btn-list"
                        :aria-pressed="saved"
                        @click="saved = !saved">
                    <span x-text="saved ? '✓' : '+'" aria-hidden="true"></span>
                    <span x-text="saved ? 'On My List' : 'Add to My List'"></span>
                </button>
            </div>

            <a href="{{ url('/pricing?mode=creator') }}" class="baze-stream-hero-subcta">Are you a filmmaker or artist? See Creator Plans →</a>
        </div>
    </section>

    <div class="baze-marquee-strip">
        <div class="baze-marquee-track">
            <span>▶ MOVIES</span><span>♪ MUSIC</span><span>▤ SERIES</span><span>◉ TRAILERS</span><span>♪ ARTISTS</span><span>▲ EVENTS</span>
            <span>▶ MOVIES</span><span>♪ MUSIC</span><span>▤ SERIES</span><span>◉ TRAILERS</span><span>♪ ARTISTS</span><span>▲ EVENTS</span>
        </div>
    </div>

    {{-- ============================================================
         CONTINUE WATCHING — landscape cards with progress
         Static demo data, shaped to swap in real watch-history later.
         ============================================================ --}}
    <section class="baze-rail-section" id="continue-watching">
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
                            <div class="baze-landscape-card">
                                <div class="baze-landscape-art">
                                    <img src="{{ asset('movies/images/'.$item['image']) }}" alt="{{ $item['title'] }}" loading="lazy">
                                    <div class="baze-landscape-scrim"></div>
                                    <button type="button" class="baze-landscape-playbtn" aria-label="Resume {{ $item['title'] }}">▶</button>
                                </div>
                                <div class="baze-landscape-info">
                                    <div class="baze-poster-title">{{ $item['title'] }}</div>
                                    <div class="baze-poster-meta">{{ $item['remaining'] }}</div>
                                    <div class="baze-progress-track" role="progressbar" aria-valuenow="{{ $item['progress'] }}" aria-valuemin="0" aria-valuemax="100" aria-label="Watch progress for {{ $item['title'] }}">
                                        <div class="baze-progress-fill" style="width: {{ $item['progress'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="baze-rail-fade baze-rail-fade--left" :class="{ 'is-visible': hasOverflow && !atStart }"></div>
                    <div class="baze-rail-fade baze-rail-fade--right" :class="{ 'is-visible': hasOverflow && !atEnd }"></div>
                </div>

                <button type="button" class="baze-rail-arrow baze-rail-arrow--next" @click="scrollByCard(1)" x-show="hasOverflow" :disabled="atEnd" aria-label="Next">›</button>
            </div>
        </div>
    </section>

    {{-- ============================================================
         TRENDING NOW — portrait poster rail
         ============================================================ --}}
    <section class="baze-rail-section" id="trending">
        <div class="baze-wrap">
            <div class="baze-rail-head">
                <div>
                    <div class="baze-eyebrow">WHAT EVERYONE'S WATCHING</div>
                    <h2 class="baze-display baze-h2">Trending Now</h2>
                </div>
            </div>

            <div class="baze-rail-shell" x-data="baseRail('.baze-poster-card')" x-init="init()">
                <button type="button" class="baze-rail-arrow baze-rail-arrow--prev" @click="scrollByCard(-1)" x-show="hasOverflow" :disabled="atStart" aria-label="Previous titles">‹</button>

                <div class="baze-rail-viewport">
                    <div class="baze-rail-track" x-ref="rail" role="region" aria-label="Trending now" tabindex="0">
                        @foreach ($trendingNow as $item)
                            <div class="baze-poster-card">
                                <div class="baze-poster-art">
                                    <img src="{{ asset('movies/images/'.$item['image']) }}" alt="{{ $item['title'] }}" loading="lazy">
                                    @if ($item['badge'])
                                        <span class="baze-poster-tag baze-poster-tag--{{ \Illuminate\Support\Str::slug($item['badge']) }}">{{ $item['badge'] }}</span>
                                    @endif
                                    <div class="baze-poster-play" aria-hidden="true">▶</div>
                                </div>
                                <div class="baze-poster-info">
                                    <div class="baze-poster-title">{{ $item['title'] }}</div>
                                    <div class="baze-poster-meta">{{ $item['year'] }} · {{ $item['genre'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="baze-rail-fade baze-rail-fade--left" :class="{ 'is-visible': hasOverflow && !atStart }"></div>
                    <div class="baze-rail-fade baze-rail-fade--right" :class="{ 'is-visible': hasOverflow && !atEnd }"></div>
                </div>

                <button type="button" class="baze-rail-arrow baze-rail-arrow--next" @click="scrollByCard(1)" x-show="hasOverflow" :disabled="atEnd" aria-label="Next titles">›</button>
            </div>
        </div>
    </section>

    {{-- ============================================================
         NEW RELEASES — portrait poster rail
         ============================================================ --}}
    <section class="baze-rail-section" id="new-releases">
        <div class="baze-wrap">
            <div class="baze-rail-head">
                <div>
                    <div class="baze-eyebrow">JUST ADDED</div>
                    <h2 class="baze-display baze-h2">New Releases</h2>
                </div>
            </div>

            <div class="baze-rail-shell" x-data="baseRail('.baze-poster-card')" x-init="init()">
                <button type="button" class="baze-rail-arrow baze-rail-arrow--prev" @click="scrollByCard(-1)" x-show="hasOverflow" :disabled="atStart" aria-label="Previous titles">‹</button>

                <div class="baze-rail-viewport">
                    <div class="baze-rail-track" x-ref="rail" role="region" aria-label="New releases" tabindex="0">
                        @foreach ($newReleases as $item)
                            <div class="baze-poster-card">
                                <div class="baze-poster-art">
                                    <img src="{{ asset('movies/images/'.$item['image']) }}" alt="{{ $item['title'] }}" loading="lazy">
                                    @if ($item['badge'])
                                        <span class="baze-poster-tag baze-poster-tag--{{ \Illuminate\Support\Str::slug($item['badge']) }}">{{ $item['badge'] }}</span>
                                    @endif
                                    <div class="baze-poster-play" aria-hidden="true">▶</div>
                                </div>
                                <div class="baze-poster-info">
                                    <div class="baze-poster-title">{{ $item['title'] }}</div>
                                    <div class="baze-poster-meta">{{ $item['year'] }} · {{ $item['genre'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="baze-rail-fade baze-rail-fade--left" :class="{ 'is-visible': hasOverflow && !atStart }"></div>
                    <div class="baze-rail-fade baze-rail-fade--right" :class="{ 'is-visible': hasOverflow && !atEnd }"></div>
                </div>

                <button type="button" class="baze-rail-arrow baze-rail-arrow--next" @click="scrollByCard(1)" x-show="hasOverflow" :disabled="atEnd" aria-label="Next titles">›</button>
            </div>
        </div>
    </section>

    {{-- ============================================================
         MOVIES — genre chips + browse rail
         Chips are visual-only for now; wire wire:click / a query
         string to $movieGenres later without touching this markup.
         ============================================================ --}}
    <section class="baze-rail-section" id="movies">
        <div class="baze-wrap">
            <div class="baze-rail-head">
                <div>
                    <div class="baze-eyebrow">BIG STORIES, FRESH RELEASES</div>
                    <h2 class="baze-display baze-h2">Movies</h2>
                    <p class="baze-rail-sub">Big stories. Fresh releases. Independent films. One place.</p>
                </div>
            </div>

            <div class="baze-genre-chips" role="tablist" aria-label="Filter movies by genre">
                @foreach ($movieGenres as $i => $genre)
                    <button type="button" class="baze-genre-chip {{ $i === 0 ? 'is-active' : '' }}" role="tab" aria-selected="{{ $i === 0 ? 'true' : 'false' }}">{{ $genre }}</button>
                @endforeach
            </div>

            <div class="baze-rail-shell" x-data="baseRail('.baze-poster-card')" x-init="init()">
                <button type="button" class="baze-rail-arrow baze-rail-arrow--prev" @click="scrollByCard(-1)" x-show="hasOverflow" :disabled="atStart" aria-label="Previous titles">‹</button>

                <div class="baze-rail-viewport">
                    <div class="baze-rail-track" x-ref="rail" role="region" aria-label="Browse movies" tabindex="0">
                        @foreach ($movieGrid as $item)
                            <div class="baze-poster-card">
                                <div class="baze-poster-art">
                                    <img src="{{ asset('movies/images/'.$item['image']) }}" alt="{{ $item['title'] }}" loading="lazy">
                                    <div class="baze-poster-play" aria-hidden="true">▶</div>
                                </div>
                                <div class="baze-poster-info">
                                    <div class="baze-poster-title">{{ $item['title'] }}</div>
                                    <div class="baze-poster-meta">{{ $item['year'] }} · {{ $item['genre'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="baze-rail-fade baze-rail-fade--left" :class="{ 'is-visible': hasOverflow && !atStart }"></div>
                    <div class="baze-rail-fade baze-rail-fade--right" :class="{ 'is-visible': hasOverflow && !atEnd }"></div>
                </div>

                <button type="button" class="baze-rail-arrow baze-rail-arrow--next" @click="scrollByCard(1)" x-show="hasOverflow" :disabled="atEnd" aria-label="Next titles">›</button>
            </div>
        </div>
    </section>

    {{-- ============================================================
         TRAILERS — click-to-play landscape rail
         Poster first; video only loads/plays on interaction.
         Only one trailer plays at a time.
         ============================================================ --}}
    <section class="baze-rail-section" id="trailers" x-data="{ playingIndex: null }">
        <div class="baze-wrap">
            <div class="baze-rail-head">
                <div>
                    <div class="baze-eyebrow">WATCH THE FIRST LOOK</div>
                    <h2 class="baze-display baze-h2">Trailers</h2>
                </div>
            </div>

            <div class="baze-rail-shell" x-data="baseRail('.baze-trailer-card')" x-init="init()">
                <button type="button" class="baze-rail-arrow baze-rail-arrow--prev" @click="scrollByCard(-1)" x-show="hasOverflow" :disabled="atStart" aria-label="Previous trailers">‹</button>

                <div class="baze-rail-viewport">
                    <div class="baze-rail-track" x-ref="rail" role="region" aria-label="Trailers" tabindex="0">
                        @foreach ($trailers as $index => $trailer)
                            <div class="baze-trailer-card">
                                <div class="baze-landscape-art">
                                    <template x-if="playingIndex !== {{ $index }}">
                                        <button type="button" class="baze-trailer-poster-btn" @click="playingIndex = {{ $index }}" aria-label="Play trailer: {{ $trailer['title'] }}">
                                            <img src="{{ asset('movies/images/'.$trailer['poster']) }}" alt="{{ $trailer['title'] }}" loading="lazy">
                                            <div class="baze-landscape-scrim"></div>
                                            <span class="baze-landscape-playbtn" aria-hidden="true">▶</span>
                                        </button>
                                    </template>
                                    <template x-if="playingIndex === {{ $index }}">
                                        <video src="{{ asset('movies/trailers/'.$trailer['video']) }}"
                                               controls
                                               autoplay
                                               muted
                                               playsinline
                                               preload="none"
                                               class="baze-trailer-video"></video>
                                    </template>
                                </div>
                                <div class="baze-landscape-info">
                                    <div class="baze-poster-title">{{ $trailer['title'] }}</div>
                                    <div class="baze-poster-meta">Watch trailer</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="baze-rail-fade baze-rail-fade--left" :class="{ 'is-visible': hasOverflow && !atStart }"></div>
                    <div class="baze-rail-fade baze-rail-fade--right" :class="{ 'is-visible': hasOverflow && !atEnd }"></div>
                </div>

                <button type="button" class="baze-rail-arrow baze-rail-arrow--next" @click="scrollByCard(1)" x-show="hasOverflow" :disabled="atEnd" aria-label="Next trailers">›</button>
            </div>
        </div>
    </section>

    {{-- ============================================================
         MUSIC — "Stream the sounds behind the stories"
         ============================================================
    <section class="baze-rail-section" id="music">
        <div class="baze-wrap">
            <div class="baze-rail-head">
                <div>
                    <div class="baze-eyebrow" style="color:var(--color-frequency)">MUSIC</div>
                    <h2 class="baze-display baze-h2">Stream the sounds behind the stories</h2>
                </div>
            </div>

            <div class="baze-rail-shell" x-data="baseRail('.baze-music-card')" x-init="init()">
                <button type="button" class="baze-rail-arrow baze-rail-arrow--prev" @click="scrollByCard(-1)" x-show="hasOverflow" :disabled="atStart" aria-label="Previous tracks">‹</button>

                <div class="baze-rail-viewport">
                    <div class="baze-rail-track" x-ref="rail" role="region" aria-label="Music" tabindex="0">
                        @foreach ($musicRails as $track)
                            <div class="baze-music-card">
                                <div class="baze-music-art">
                                    <img src="{{ asset('home/'.$track['cover']) }}" alt="{{ $track['track'] }} cover art" loading="lazy">
                                    <div class="baze-music-play" aria-hidden="true">▶</div>
                                </div>
                                <div class="baze-music-info">
                                    <div class="baze-poster-title">{{ $track['track'] }}</div>
                                    <div class="baze-poster-meta">{{ $track['artist'] }} · {{ $track['duration'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="baze-rail-fade baze-rail-fade--left" :class="{ 'is-visible': hasOverflow && !atStart }"></div>
                    <div class="baze-rail-fade baze-rail-fade--right" :class="{ 'is-visible': hasOverflow && !atEnd }"></div>
                </div>

                <button type="button" class="baze-rail-arrow baze-rail-arrow--next" @click="scrollByCard(1)" x-show="hasOverflow" :disabled="atEnd" aria-label="Next tracks">›</button>
            </div>
        </div>
    </section>
    --}}

    {{-- ============================================================
         WATCH / LISTEN / CREATE
         ============================================================
    <section class="baze-section baze-section--tight">
        <div class="baze-wrap">
            <div class="baze-triptych">
                <div class="baze-triptych-card">
                    <div class="baze-triptych-icon" style="color:var(--color-sunburst)">▶</div>
                    <h4 class="baze-display">WATCH</h4>
                    <p>Movies, series, documentaries, and trailers — new titles added every week.</p>
                    <a href="#trending" class="baze-triptych-link" style="color:var(--color-sunburst)">Browse titles →</a>
                </div>
                <div class="baze-triptych-card">
                    <div class="baze-triptych-icon" style="color:var(--color-frequency)">♪</div>
                    <h4 class="baze-display">LISTEN</h4>
                    <p>Music from independent and established artists across East Africa.</p>
                    <a href="#music" class="baze-triptych-link" style="color:var(--color-frequency)">Hear what's new →</a>
                </div>
                <div class="baze-triptych-card">
                    <div class="baze-triptych-icon" style="color:var(--color-mustard)">＋</div>
                    <h4 class="baze-display">CREATE</h4>
                    <p>Upload your film, album, or event and get paid straight to M-Pesa.</p>
                    <a href="#plans" class="baze-triptych-link" style="color:var(--color-mustard)">See creator plans →</a>
                </div>
            </div>
        </div>
    </section>
    --}}

    {{-- ============================================================
         AFRICAN STORIES — creator spotlight
         Folds in the original "made for movie makers" cinema block.
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
         LISTENER STRIP (unchanged)
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

    {{-- ============================================================
         Reusable rail-scroll Alpine component.
         Wrapped in @once so it only renders if this partial is ever
         included more than once on a page. Consider moving this
         function into resources/js/app.js as a permanent global if
         you add more rails elsewhere on the site.
         ============================================================ --}}
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
        </script>
    @endonce
</div>
