<div>
    {{-- ============================================================
         HERO — "The Turntable"
         Signature moment: an idling vinyl (record.png) as the hero
         visual, framed like a turntable platter with a tonearm.
         ============================================================ --}}
    <section class="baze-hero">
        <div class="baze-wrap baze-hero-grid">
            <div>
                <div class="baze-eyebrow">MUSIC · VIDEO · PODCASTS · EVENTS · CINEMA</div>
                <h1 class="baze-display baze-hero-h1">
                    TURN YOUR SOUND INTO <span class="baze-accent">INCOME</span>
                </h1>
                <p class="baze-lede">
                    DobaPlay is East Africa's distribution home for artists, studios, and record labels.
                    Upload once, get paid everywhere — one yearly plan, no hidden cuts.
                </p>
                <div class="baze-hero-ctas">
                    <a href="#plans" class="baze-btn baze-btn-primary">Pick your plan &nbsp;↓</a>
                    <a href="{{ route('advertise') }}" class="baze-btn baze-btn-outline-freq">▶ See how payouts work</a>
                </div>
                <div class="baze-trust-row">
                    <span><b>5</b>plan types for every creator</span>
                    <span><b>M-Pesa</b>instant payouts</span>
                    <span><b>0</b>upload fees</span>
                </div>
            </div>

            <div class="baze-turntable-stage">
                <div class="baze-platter-glow"></div>
                <div class="baze-badge-chip baze-chip-1">
                    <span class="baze-dot" style="background:var(--color-frequency)"></span>
                    Now streaming: 12,400+ tracks
                </div>
                <div class="baze-tonearm"></div>
                <div class="baze-vinyl-wrap">
                    <img src="{{ asset('home/record.png') }}" alt="DobaPlay record" loading="eager">
                </div>
                <div class="baze-badge-chip baze-chip-2">
                    <span class="baze-dot" style="background:var(--color-sunburst)"></span>
                    Payouts every Friday
                </div>
            </div>
        </div>
    </section>

    <div class="baze-marquee-strip">
        <div class="baze-marquee-track">
            <span>♪ ARTISTS</span><span>▤ STUDIOS</span><span>◉ RECORD LABELS</span><span>▲ EVENTS</span><span>▶ CINEMA</span>
            <span>♪ ARTISTS</span><span>▤ STUDIOS</span><span>◉ RECORD LABELS</span><span>▲ EVENTS</span><span>▶ CINEMA</span>
        </div>
    </div>

    {{-- ============================================================
     PLANS — "The Crate"
     Five plan cards styled as album sleeves in a record crate,
     each with a colour-coded spine and a circular label icon.
     Horizontally scrollable carousel: all cards in one line when
     they fit, swipe/scroll + arrows when they don't.
     ============================================================ --}}
    <section class="baze-section" id="plans">
        <div class="baze-wrap">
            <div class="baze-section-head">
                <div class="baze-eyebrow" style="justify-content:center">PICK YOUR LANE</div>
                <h2 class="baze-display baze-h2">The crate. Flip through and find your plan.</h2>
                <p>One yearly plan. Everything you need to upload, distribute, and get paid — with no monthly juggling.</p>
            </div>

            <div
                x-data="{
                atStart: true,
                atEnd: false,
                hasOverflow: false,
                activeIndex: 0,
                cardCount: {{ count($plans) }},
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
                    const card = rail.querySelector('.baze-record-card');
                    if (card) {
                        const step = card.getBoundingClientRect().width + 16;
                        this.activeIndex = Math.max(0, Math.round(rail.scrollLeft / step));
                    }
                },
                scrollByCard(direction) {
                    const rail = this.$refs.rail;
                    const card = rail.querySelector('.baze-record-card');
                    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                    const step = card ? card.getBoundingClientRect().width + 16 : rail.clientWidth * 0.85;
                    rail.scrollBy({ left: direction * step, behavior: reduceMotion ? 'auto' : 'smooth' });
                }
            }"
                x-init="init()"
            >
                <div class="baze-plans-carousel">
                    <button
                        type="button"
                        class="baze-plans-arrow baze-plans-arrow--prev"
                        @click="scrollByCard(-1)"
                        x-show="hasOverflow"
                        :disabled="atStart"
                        aria-label="Previous plans"
                    >‹</button>

                    <div class="baze-plans-viewport">
                        <div
                            class="baze-crate-rail"
                            x-ref="rail"
                            role="region"
                            aria-label="Pricing plans"
                            tabindex="0"
                        >
                            @foreach ($plans as $plan)
                                <div class="baze-record-card @if($plan['popular']) baze-record-card--wide @endif">
                                    <div class="baze-spine baze-spine-{{ $plan['spine'] }}"></div>

                                    @if ($plan['popular'])
                                        <div class="baze-ribbon">POPULAR</div>
                                    @endif

                                    <div class="baze-disc-icon">
                                        <img src="{{ asset('home/'.$plan['icon']) }}" alt="{{ $plan['title'] }}">
                                    </div>

                                    <div class="baze-plan-title">{{ $plan['title'] }}</div>
                                    <div class="baze-plan-price">
                                        <span class="baze-plan-cur">KES</span>{{ $plan['price'] }}
                                    </div>
                                    <div class="baze-plan-billed">BILLED YEARLY</div>

                                    <ul class="baze-plan-features">
                                        @foreach ($plan['features'] as $feature)
                                            <li>✓ {{ $feature }}</li>
                                        @endforeach
                                    </ul>

                                    <a href="{{ route('register') }}"
                                       class="baze-plan-cta"
                                       style="background:{{ $plan['cta_bg'] }};color:{{ $plan['cta_text'] }}">
                                        Create account
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <div class="baze-plans-fade baze-plans-fade--left" :class="{ 'is-visible': hasOverflow && !atStart }"></div>
                        <div class="baze-plans-fade baze-plans-fade--right" :class="{ 'is-visible': hasOverflow && !atEnd }"></div>
                    </div>

                    <button
                        type="button"
                        class="baze-plans-arrow baze-plans-arrow--next"
                        @click="scrollByCard(1)"
                        x-show="hasOverflow"
                        :disabled="atEnd"
                        aria-label="Next plans"
                    >›</button>
                </div>

                <div class="baze-plans-dots" x-show="hasOverflow" aria-hidden="true">
                    <template x-for="i in cardCount" :key="i">
                        <span class="baze-plans-dot" :class="{ 'is-active': (i - 1) === activeIndex }"></span>
                    </template>
                </div>

                <p class="baze-plans-hint" x-show="showHint && hasOverflow" x-transition.opacity>Swipe to explore →</p>
            </div>
        </div>
    </section>

    {{-- ============================================================
         CINEMA SPOTLIGHT
         ============================================================ --}}
    <section class="baze-section baze-section--tight" id="cinema">
        <div class="baze-wrap">
            <div class="baze-spotlight">
                <div class="baze-spotlight-copy">
                    <div class="baze-eyebrow baze-eyebrow--mustard">NOW SCREENING</div>
                    <h3 class="baze-display baze-h3">MADE FOR MOVIE MAKERS, TOO</h3>
                    <p>
                        The Cinema plan brings full VOD distribution to filmmakers — set your own rental
                        or pay-per-view pricing, ship subtitles in multiple languages, and promote your
                        premiere to a built-in audience.
                    </p>
                    <div class="baze-spotlight-tags">
                        <span class="baze-tag">Rental &amp; PPV pricing</span>
                        <span class="baze-tag">Multi-language subtitles</span>
                        <span class="baze-tag">Premiere promotion</span>
                    </div>
                    <a href="{{ route('register') }}" class="baze-btn baze-btn-primary" style="background:var(--color-mustard)">
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
         WHY IT MATTERS — "The Signal Chain"
         ============================================================ --}}
    <section class="baze-section" id="why">
        <div class="baze-wrap">
            <div class="baze-section-head">
                <div class="baze-eyebrow" style="justify-content:center">THE SIGNAL CHAIN</div>
                <h2 class="baze-display baze-h2">Why the yearly plan pays for itself</h2>
                <p>Every plan runs through the same chain — from upload to payout — so nothing gets lost along the way.</p>
            </div>

            <div class="baze-chain">
                @foreach ($signalChain as $node)
                    <div class="baze-chain-node">
                        <div class="baze-node-icon">
                            @switch($node['icon'])
                                @case('storage')
                                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#29E0C8" stroke-width="1.8"><ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v14c0 1.7 3.6 3 8 3s8-1.3 8-3V5"/><path d="M4 12c0 1.7 3.6 3 8 3s8-1.3 8-3"/></svg>
                                    @break
                                @case('bolt')
                                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#29E0C8" stroke-width="1.8"><path d="M3 12h4l2-8 4 16 2-10 2 6h4"/></svg>
                                    @break
                                @case('shield')
                                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#29E0C8" stroke-width="1.8"><path d="M12 2l8 4v6c0 5-3.4 8.5-8 10-4.6-1.5-8-5-8-10V6z"/></svg>
                                    @break
                                @case('copyright')
                                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#29E0C8" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 3v18M3 12h18"/></svg>
                                    @break
                                @case('wallet')
                                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#29E0C8" stroke-width="1.8"><rect x="3" y="6" width="18" height="12" rx="2"/><path d="M3 10h18M7 15h4"/></svg>
                                    @break
                            @endswitch
                        </div>
                        <h4>{{ $node['title'] }}</h4>
                        <p>{{ $node['body'] }}</p>
                    </div>
                @endforeach
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
                <a href="{{ route('premium') }}" class="baze-btn baze-btn-outline-freq">See listener plans →</a>
            </div>
        </div>
    </section>
</div>
