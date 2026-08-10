<div
    x-data="{
        activeModal: null,
        open(modal, type = null, price = null) {
            this.activeModal = modal;
            if (modal === 'video') {
                this.$nextTick(() => {
                    window.dispatchEvent(new CustomEvent('video-ad-selected', { detail: { type, price } }));
                });
            }
        },
        close() { this.activeModal = null; }
    }"
    @keydown.escape.window="close()"
    class="min-h-screen"
    style="color: var(--color-text-primary); font-family: var(--font-sans);"
>
    {{-- HERO --}}
    <section class="baze-hero text-center">
        <div class="baze-wrap">
            <div class="baze-eyebrow baze-eyebrow--mustard" style="justify-content:center; color: var(--color-hibiscus); margin-bottom: 22px;">
                <span style="background: var(--color-hibiscus);"></span>
                Advertise on DobaPlay
            </div>
            <h1 class="baze-display mb-8" style="font-size: clamp(2.75rem, 7vw, 5.5rem); line-height: 0.95;">
                Get in front of<br>
                <span style="color: var(--color-hibiscus);">real listeners.</span>
            </h1>
            <p class="max-w-2xl mx-auto text-base md:text-lg" style="color: var(--color-text-secondary); line-height: 1.65;">
                Banner, audio, interstitial, and rewarded placements — targeted from
                national level down to counties, sub-counties, and wards. Pick a
                format below to build your campaign.
            </p>
        </div>
    </section>

    {{-- FLASH MESSAGES --}}
    @if (session('success') || session('error'))
        <section class="baze-wrap pb-10">
            <div class="max-w-xl mx-auto text-center text-sm rounded-lg px-4 py-3"
                 style="background: var(--color-bg-card-start); border: 1px solid var(--color-border); color: {{ session('success') ? 'var(--color-frequency)' : 'var(--color-hibiscus)' }};">
                {{ session('success') ?? session('error') }}
            </div>
        </section>
    @endif

    {{-- AD FORMAT CAROUSEL --}}
    <section class="baze-section baze-section--tight" style="padding-top: 8px; padding-bottom: 40px;">
        <div class="baze-wrap">
            <div class="baze-section-head" style="margin-bottom: 44px;">
                <div class="baze-eyebrow" style="justify-content:center; color: var(--color-hibiscus);">
                    ADVERTISING FORMATS
                </div>
                <h2 class="baze-display baze-h2">Pick a format, build your campaign.</h2>
            </div>

            @php
                $adAccents = ['sun', 'freq', 'hib', 'mus'];
                $adColors = ['var(--color-sunburst)', 'var(--color-frequency)', 'var(--color-hibiscus)', 'var(--color-mustard)'];
            @endphp

            <div
                x-data="{
                    atStart: true, atEnd: false, hasOverflow: false, activeIndex: 0,
                    cardCount: {{ count($adTypes) }}, showHint: true,
                    init() {
                        this.measure();
                        this.$refs.rail.addEventListener('scroll', () => this.onScroll(), { passive: true });
                        if (window.ResizeObserver) { new ResizeObserver(() => this.measure()).observe(this.$refs.rail); }
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
                    <button type="button" class="baze-plans-arrow" @click="scrollByCard(-1)" x-show="hasOverflow" :disabled="atStart" aria-label="Previous ad formats">‹</button>

                    <div class="baze-plans-viewport">
                        <div class="baze-crate-rail" x-ref="rail" role="region" aria-label="Advertising formats" tabindex="0">
                            @foreach ($adTypes as $i => $ad)
                                @php $accent = $adAccents[$i % 4]; $accentColor = $adColors[$i % 4]; @endphp
                                <div class="baze-record-card @if(! empty($ad['badge'])) baze-record-card--wide @endif">
                                    <div class="baze-spine baze-spine-{{ $accent }}"></div>

                                    @if (! empty($ad['badge']))
                                        <div class="baze-ribbon" style="background: {{ $accentColor }}; color: var(--color-ink);">{{ $ad['badge'] }}</div>
                                    @endif

                                    <div class="uppercase text-xs font-semibold mb-2" style="color: var(--color-text-secondary); letter-spacing: 0.08em;">
                                        {{ $ad['title'] }}
                                    </div>

                                    <div class="baze-plan-price">
                                        <span class="baze-plan-cur">KES</span>{{ rtrim(rtrim(number_format($ad['price'], 2), '0'), '.') }}
                                    </div>
                                    <div class="baze-plan-sub" style="color: {{ $accentColor }};">
                                        {{ $ad['billing'] }}
                                    </div>

                                    <ul class="baze-plan-features">
                                        @foreach ($ad['features'] as $feature)
                                            <li><span style="color: {{ $accentColor }}; flex-shrink:0;">/</span> {{ $feature }}</li>
                                        @endforeach
                                    </ul>

                                    <button
                                        type="button"
                                        @click="open('{{ $ad['modal'] }}', '{{ $ad['key'] }}', {{ $ad['price'] }})"
                                        class="baze-plan-cta"
                                        style="background: {{ $accentColor }}; color: var(--color-ink);"
                                    >
                                        Buy now
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <div class="baze-plans-fade baze-plans-fade--left" :class="{ 'is-visible': hasOverflow && !atStart }"></div>
                        <div class="baze-plans-fade baze-plans-fade--right" :class="{ 'is-visible': hasOverflow && !atEnd }"></div>
                    </div>

                    <button type="button" class="baze-plans-arrow" @click="scrollByCard(1)" x-show="hasOverflow" :disabled="atEnd" aria-label="Next ad formats">›</button>
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

    {{-- WHY ADVERTISE --}}
    <section class="baze-section" style="padding-top: 24px;">
        <div class="baze-wrap">
            <div class="baze-section-head" style="margin-bottom: 44px;">
                <h3 class="baze-display baze-h3" style="text-align:center;">
                    Why advertise with DobaPlay
                </h3>
            </div>

            <ul class="max-w-2xl mx-auto space-y-7 text-sm" style="color: var(--color-text-secondary);">
                <li class="flex gap-3">
                    <i class="fa-solid fa-bullhorn mt-1" style="color: var(--color-hibiscus);"></i>
                    Reach a highly engaged music audience across the platform
                </li>
                <li class="flex gap-3">
                    <i class="fa-solid fa-chart-line mt-1" style="color: var(--color-hibiscus);"></i>
                    Track ad performance and maximize ROI with analytics
                </li>
                <li class="flex gap-3">
                    <i class="fa-solid fa-shield-halved mt-1" style="color: var(--color-hibiscus);"></i>
                    Safe and secure ad placements
                </li>
                <li class="flex gap-3">
                    <i class="fa-solid fa-clock mt-1" style="color: var(--color-hibiscus);"></i>
                    Flexible campaigns tailored to your schedule and budget
                </li>
                <li class="flex gap-3">
                    <i class="fa-solid fa-users mt-1" style="color: var(--color-hibiscus);"></i>
                    Targeted campaigns built around your audience
                </li>
            </ul>
        </div>
    </section>

    {{-- MODALS — Alpine-native, no Bootstrap JS (unchanged) --}}
    @php
        $modals = [
            'banner' => ['label' => 'Create banner ad', 'icon' => 'fa-bullhorn', 'component' => 'ads.create-banner-ad'],
            'audio' => ['label' => 'Create audio ad', 'icon' => 'fa-volume-up', 'component' => 'audio.create-audio-ad'],
            'video' => ['label' => 'Create video ad', 'icon' => 'fa-video', 'component' => 'video.create-video-ad'],
        ];
    @endphp

    @foreach ($modals as $key => $modal)
        <div
            x-show="activeModal === '{{ $key }}'"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            role="dialog"
            aria-modal="true"
            aria-labelledby="{{ $key }}-modal-title"
        >
            <div
                x-show="activeModal === '{{ $key }}'"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="close()"
                class="absolute inset-0"
                style="background: rgba(18, 15, 13, 0.8); backdrop-filter: blur(4px);"
            ></div>

            <div
                x-show="activeModal === '{{ $key }}'"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-lg max-h-[90vh] overflow-y-auto rounded-xl"
                style="background: linear-gradient(180deg, var(--color-bg-card-start), var(--color-bg-card-end)); border: 1px solid var(--color-border); box-shadow: var(--shadow-xl);"
            >
                <div class="flex items-center justify-between px-6 py-4" style="border-bottom: 1px solid var(--color-border);">
                    <h5 id="{{ $key }}-modal-title" class="font-bold flex items-center gap-2" style="color: var(--color-hibiscus); font-family: var(--font-display); font-size: var(--font-size-xl); letter-spacing: 0.02em;">
                        <i class="fas {{ $modal['icon'] }}"></i>
                        {{ $modal['label'] }}
                    </h5>
                    <button type="button" @click="close()" aria-label="Close" class="text-2xl leading-none" style="color: var(--color-text-muted);">
                        &times;
                    </button>
                </div>

                <div class="p-6">
                    @livewire($modal['component'])
                </div>
            </div>
        </div>
    @endforeach
</div>
