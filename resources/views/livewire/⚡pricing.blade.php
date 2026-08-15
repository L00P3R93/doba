<div
    x-data="{
        mode: $wire.entangle('mode'),
        get isCreator() { return this.mode === 'creator' },
        drag(e) {
            const track = this.$refs.faderTrack.getBoundingClientRect();
            const x = (e.touches ? e.touches[0].clientX : e.clientX) - track.left;
            this.mode = (x / track.width) > 0.5 ? 'creator' : 'listener';
        }
    }"
    class="min-h-screen"
    style="background: linear-gradient(180deg, var(--color-bg-dark-start), var(--color-bg-dark-end)); color: var(--color-text-primary); font-family: var(--font-sans);"
>
    {{-- ============================================================
         HERO — "The Turntable"
         Signature moment: an idling vinyl (record.png) as the hero
         visual, framed like a turntable platter with a tonearm.
         ============================================================
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
    --}}

    {{-- OLD HERO --}}
    <section class="baze-hero">
        <div class="baze-wrap pt-28 pb-16 text-center">
            <div class="baze-eyebrow" style="justify-content:center; margin-bottom: 22px;">
                Choose your signal
            </div>
            <h1 class="font-bold leading-[0.95] mb-8" style="font-family: var(--font-display); font-size: clamp(2.75rem, 7vw, 5.5rem); letter-spacing: 0.01em;">
                One deck.<br>
                Two ways to
                <span :style="isCreator ? 'color: var(--color-sunburst)' : 'color: var(--color-frequency)'" x-text="isCreator ? 'get paid.' : 'get in.'"></span>
            </h1>
            <p class="max-w-xl mx-auto text-base md:text-lg" style="color: var(--color-text-secondary); line-height: 1.65;">
                Slide the fader to switch between listening plans and creator plans —
                same platform, two different rooms.
            </p>
        </div>
    </section>

    {{-- FADER TOGGLE --}}
    <section class="baze-section">
        <div class="baze-wrap">
            <div class="max-w-md mx-auto">
                <div class="flex justify-between text-xs font-semibold uppercase tracking-widest mb-5">
                    <button
                        type="button"
                        @click="mode = 'listener'"
                        :aria-pressed="!isCreator"
                        :style="!isCreator ? 'color: var(--color-frequency)' : 'color: var(--color-text-faint)'"
                        class="baze-fader-label rounded px-1 -mx-1 focus-visible:outline-none focus-visible:ring-2"
                        style="--tw-ring-color: var(--color-frequency);"
                    >
                        Listener
                    </button>
                    <button
                        type="button"
                        @click="mode = 'creator'"
                        :aria-pressed="isCreator"
                        :style="isCreator ? 'color: var(--color-sunburst)' : 'color: var(--color-text-faint)'"
                        class="baze-fader-label rounded px-1 -mx-1 focus-visible:outline-none focus-visible:ring-2"
                        style="--tw-ring-color: var(--color-sunburst);"
                    >
                        Creator
                    </button>
                </div>

                <div
                    x-ref="faderTrack"
                    @click="drag($event)"
                    class="baze-fader-track relative h-12 rounded-full cursor-pointer select-none"
                    style="background: var(--color-bg-card-start); border: 1px solid var(--color-border);"
                    role="slider"
                    aria-label="Toggle between listener and creator pricing"
                    :aria-valuenow="isCreator ? 100 : 0"
                    aria-valuemin="0"
                    aria-valuemax="100"
                    aria-valuetext="isCreator ? 'Creator plans' : 'Listener plans'"
                    tabindex="0"
                    @keydown.left="mode = 'listener'"
                    @keydown.right="mode = 'creator'"
                >
                    <div class="absolute inset-0 flex items-center justify-between px-4 pointer-events-none opacity-30">
                        <template x-for="n in 9" :key="n">
                            <div class="w-px h-3" style="background: var(--color-text-faint);"></div>
                        </template>
                    </div>

                    <div
                        class="absolute top-1 h-10 w-10 rounded-full shadow-lg transition-all"
                        style="transition-duration: var(--transition-spring, 400ms); transition-timing-function: cubic-bezier(.34,1.56,.64,1);"
                        :style="isCreator
                        ? 'left: calc(100% - 44px); background: var(--color-sunburst); box-shadow: var(--shadow-gold);'
                        : 'left: 4px; background: var(--color-frequency); box-shadow: var(--shadow-frequency);'"
                    ></div>
                </div>
            </div>
        </div>
    </section>

    {{-- FLASH MESSAGES --}}
    @if (session('success') || session('error'))
        <section class="baze-section">
            <div class="baze-wrap">
                <div class="max-w-xl mx-auto text-center text-sm rounded-lg px-4 py-3"
                     style="background: var(--color-bg-card-start); border: 1px solid var(--color-border); color: {{ session('success') ? 'var(--color-frequency)' : 'var(--color-hibiscus)' }};">
                    {{ session('success') ?? session('error') }}
                </div>
            </div>
        </section>
    @endif

    {{-- PLAN CAROUSEL --}}
    <section class="baze-section baze-section--tight" id="plans" style="padding-bottom: 40px;">
        <div class="baze-wrap">
            <div class="baze-section-head" style="margin-bottom: 44px;">
                <div class="baze-eyebrow justify-content-center" :style="isCreator ? 'justify-content:center; color: var(--color-sunburst)' : 'justify-content:center; color: var(--color-frequency)'">
                    <span x-text="isCreator ? 'CREATOR PLANS' : 'LISTENER PLANS'"></span>
                </div>
                <h2 class="baze-display baze-h2" x-text="isCreator ? 'Get paid for every upload.' : 'Pick your listening plan.'"></h2>
            </div>

            {{-- Listener rail --}}
            <div
                x-show="!isCreator"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                x-data="{
                    atStart: true, atEnd: false, hasOverflow: false, activeIndex: 0,
                    cardCount: {{ count($listenerPlans) }}, showHint: true,
                    init() {
                        this.measure();
                        this.$refs.rail.addEventListener('scroll', () => this.onScroll(), { passive: true });
                        if (window.ResizeObserver) { new ResizeObserver(() => this.measure()).observe(this.$refs.rail); }
                        window.addEventListener('resize', () => this.measure());
                        this.$watch('mode', () => this.$nextTick(() => this.measure()));
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
                    <button type="button" class="baze-plans-arrow" @click="scrollByCard(-1)" x-show="hasOverflow" :disabled="atStart" aria-label="Previous plans">‹</button>

                    <div class="baze-plans-viewport">
                        <div class="baze-crate-rail" x-ref="rail" role="region" aria-label="Listener pricing plans" tabindex="0">
                            @foreach ($listenerPlans as $plan)
                                <div class="baze-record-card @if(! empty($plan['badge'])) baze-record-card--wide @endif">
                                    <div class="baze-spine baze-spine-freq"></div>

                                    @if (! empty($plan['badge']))
                                        <div class="baze-ribbon" style="background: var(--color-frequency); color: var(--color-ink);">{{ $plan['badge'] }}</div>
                                    @endif

                                    <div class="uppercase text-xs font-semibold mb-2" style="color: var(--color-text-secondary); letter-spacing: 0.08em;">
                                        {{ $plan['title'] }}
                                    </div>

                                    <div class="baze-plan-price">
                                        <span class="baze-plan-cur">KES</span>{{ number_format($plan['price']) }}
                                    </div>
                                    <div class="baze-plan-sub" style="color: var(--color-frequency);">
                                        {{ $plan['downloads'] }}
                                    </div>

                                    <ul class="baze-plan-features">
                                        @foreach ($plan['features'] as $feature)
                                            <li><span style="color: var(--color-frequency); flex-shrink:0;">/</span> {{ $feature }}</li>
                                        @endforeach
                                    </ul>

                                    @auth
                                        <form action="{{ route('subscribe.pay') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="account_no" value="{{ auth()->user()->account_no }}">
                                            <input type="hidden" name="subscription_id" value="premium">
                                            <input type="hidden" name="plan" value="{{ $plan['title'] }}">
                                            <input type="hidden" name="amount" value="{{ $plan['price'] }}">
                                            <button type="submit" class="baze-plan-cta" style="background: var(--color-frequency); color: var(--color-ink);">Subscribe</button>
                                        </form>
                                    @else
                                        <a href="{{ route('register', ['plan' => $plan['key'], 'mode' => 'listener']) }}" class="baze-plan-cta" style="background: var(--color-frequency); color: var(--color-ink);">
                                            Create account
                                        </a>
                                    @endauth
                                </div>
                            @endforeach
                        </div>

                        <div class="baze-plans-fade baze-plans-fade--left" :class="{ 'is-visible': hasOverflow && !atStart }"></div>
                        <div class="baze-plans-fade baze-plans-fade--right" :class="{ 'is-visible': hasOverflow && !atEnd }"></div>
                    </div>

                    <button type="button" class="baze-plans-arrow" @click="scrollByCard(1)" x-show="hasOverflow" :disabled="atEnd" aria-label="Next plans">›</button>
                </div>

                <div class="baze-plans-dots" x-show="hasOverflow" aria-hidden="true">
                    <template x-for="i in cardCount" :key="i">
                        <span class="baze-plans-dot" :class="{ 'is-active': (i - 1) === activeIndex }"></span>
                    </template>
                </div>
                <p class="baze-plans-hint" x-show="showHint && hasOverflow" x-transition.opacity>Swipe to explore →</p>
            </div>

            {{-- Creator rail --}}
            <div
                x-show="isCreator"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                x-data="{
                    atStart: true, atEnd: false, hasOverflow: false, activeIndex: 0,
                    cardCount: {{ count($creatorPlans) }}, showHint: true,
                    init() {
                        this.measure();
                        this.$refs.rail.addEventListener('scroll', () => this.onScroll(), { passive: true });
                        if (window.ResizeObserver) { new ResizeObserver(() => this.measure()).observe(this.$refs.rail); }
                        window.addEventListener('resize', () => this.measure());
                        this.$watch('mode', () => this.$nextTick(() => this.measure()));
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
                    <button type="button" class="baze-plans-arrow" @click="scrollByCard(-1)" x-show="hasOverflow" :disabled="atStart" aria-label="Previous plans">‹</button>

                    <div class="baze-plans-viewport">
                        <div class="baze-crate-rail" x-ref="rail" role="region" aria-label="Creator pricing plans" tabindex="0">
                            @foreach ($creatorPlans as $plan)
                                <div class="baze-record-card @if(! empty($plan['badge'])) baze-record-card--wide @endif">
                                    <div class="baze-spine @if(! empty($plan['badge'])) baze-spine-mus @else baze-spine-sun @endif"></div>

                                    @if (! empty($plan['badge']))
                                        <div class="baze-ribbon" style="background: var(--color-mustard); color: var(--color-ink);">{{ $plan['badge'] }}</div>
                                    @endif

                                    <div class="uppercase text-xs font-semibold mb-2" style="color: var(--color-text-secondary); letter-spacing: 0.08em;">
                                        {{ $plan['title'] }}
                                    </div>

                                    <div class="baze-plan-price">
                                        <span class="baze-plan-cur">KES</span>{{ number_format($plan['price']) }}
                                    </div>
                                    <div class="baze-plan-sub" style="color: var(--color-sunburst);">
                                        Billed yearly
                                    </div>

                                    <ul class="baze-plan-features">
                                        @foreach ($plan['features'] as $feature)
                                            <li><span style="color: var(--color-sunburst); flex-shrink:0;">/</span> {{ $feature }}</li>
                                        @endforeach
                                    </ul>

                                    @auth
                                        <form action="{{ route('subscribe.pay') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="account_no" value="{{ auth()->user()->account_no }}">
                                            <input type="hidden" name="subscription_id" value="1">
                                            <input type="hidden" name="plan" value="{{ $plan['title'] }}">
                                            <input type="hidden" name="amount" value="{{ $plan['price'] }}">
                                            <button type="submit" class="baze-plan-cta" style="background: var(--color-sunburst); color: var(--color-ink);">Pay now</button>
                                        </form>
                                    @else
                                        <a href="{{ route('register', ['plan' => $plan['key'], 'mode' => 'creator']) }}" class="baze-plan-cta" style="background: var(--color-sunburst); color: var(--color-ink);">
                                            Create account
                                        </a>
                                    @endauth
                                </div>
                            @endforeach
                        </div>

                        <div class="baze-plans-fade baze-plans-fade--left" :class="{ 'is-visible': hasOverflow && !atStart }"></div>
                        <div class="baze-plans-fade baze-plans-fade--right" :class="{ 'is-visible': hasOverflow && !atEnd }"></div>
                    </div>

                    <button type="button" class="baze-plans-arrow" @click="scrollByCard(1)" x-show="hasOverflow" :disabled="atEnd" aria-label="Next plans">›</button>
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

    {{-- WHY IT MATTERS
    <section class="baze-section">
        <div class="baze-wrap">
            <div class="baze-section-head" style="margin-bottom: 44px;">
                <h3 class="baze-display baze-h3" style="text-align:center;">
                    Why the yearly plan pays off
                </h3>
            </div>

            <ul class="max-w-2xl mx-auto space-y-7 text-sm" style="color: var(--color-text-secondary);">
                <li class="flex gap-3">
                    <i class="fa-solid fa-database mt-1" style="color: var(--color-frequency);"></i>
                    Secure storage for every music and video upload
                </li>
                <li class="flex gap-3">
                    <i class="fa-solid fa-gauge-high mt-1" style="color: var(--color-frequency);"></i>
                    High-speed streaming and bandwidth performance
                </li>
                <li class="flex gap-3">
                    <i class="fa-solid fa-shield-halved mt-1" style="color: var(--color-frequency);"></i>
                    Platform security and content protection
                </li>
                <li class="flex gap-3">
                    <i class="fa-solid fa-copyright mt-1" style="color: var(--color-frequency);"></i>
                    Copyright detection that protects original work and blocks unauthorized uploads
                </li>
                <li class="flex gap-3">
                    <i class="fa-solid fa-money-bill-transfer mt-1" style="color: var(--color-frequency);"></i>
                    Artist payouts, transaction fees, and payment processing handled for you
                </li>
            </ul>
        </div>
    </section>
    --}}
</div>
