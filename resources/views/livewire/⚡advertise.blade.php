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
    class="min-h-screen baze-section baze-section--tight"
    style="color: var(--color-text-primary); font-family: var(--font-sans); padding-bottom: 0;"
>
    {{-- HERO --}}
    <section class="baze-wrap pt-16 pb-16 text-center">
        <div class="baze-eyebrow baze-eyebrow--mustard" style="justify-content:center; color: var(--color-hibiscus);">
            <span style="background: var(--color-hibiscus);"></span>
            Advertise on DobaPlay
        </div>
        <h1 class="baze-display mt-5 mb-6" style="font-size: clamp(2.75rem, 7vw, 5.5rem); line-height: 0.95;">
            Get in front of<br>
            <span style="color: var(--color-hibiscus);">real listeners.</span>
        </h1>
        <p class="max-w-2xl mx-auto text-base md:text-lg" style="color: var(--color-text-secondary); line-height: 1.65;">
            Banner, audio, interstitial, and rewarded placements — targeted from
            national level down to counties, sub-counties, and wards. Pick a
            format below to build your campaign.
        </p>
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

    {{-- AD FORMAT CARDS --}}
    <section class="baze-wrap pb-24">
        <div class="flex flex-wrap justify-center gap-8">
            @foreach ($adTypes as $ad)
                <div class="card flex flex-col w-full sm:w-[46%] lg:w-[23%] lg:min-w-[220px]">
                    @if (! empty($ad['badge']))
                        <span class="self-start mb-3 px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wide"
                              style="background: var(--color-mustard); color: #06161f;">
                            {{ $ad['badge'] }}
                        </span>
                    @endif

                    <div class="uppercase text-sm font-semibold mb-1" style="color: var(--color-text-secondary);">
                        {{ $ad['title'] }}
                    </div>

                    <div class="mb-1" style="font-family: var(--font-display); font-size: var(--font-size-4xl); letter-spacing: 0.02em;">
                        <span class="align-top text-sm mr-1" style="color: var(--color-text-muted);">KES</span>{{ rtrim(rtrim(number_format($ad['price'], 2), '0'), '.') }}
                    </div>

                    <div class="text-xs mb-6" style="color: var(--color-hibiscus);">
                        {{ $ad['billing'] }}
                    </div>

                    <ul class="mb-8 space-y-2 text-sm flex-1" style="color: var(--color-text-secondary);">
                        @foreach ($ad['features'] as $feature)
                            <li class="flex gap-2">
                                <span style="color: var(--color-hibiscus);">/</span>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>

                    <button
                        type="button"
                        @click="open('{{ $ad['modal'] }}', '{{ $ad['key'] }}', {{ $ad['price'] }})"
                        class="btn btn-primary w-full"
                    >
                        Buy now
                    </button>
                </div>
            @endforeach
        </div>
    </section>

    {{-- WHY ADVERTISE --}}
    <section class="baze-wrap pb-24">
        <div class="baze-section-head" style="margin-bottom: 40px;">
            <h3 class="baze-display baze-h3" style="text-align:center;">
                Why advertise with DobaPlay
            </h3>
        </div>

        <ul class="max-w-2xl mx-auto space-y-6 text-sm" style="color: var(--color-text-secondary);">
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
