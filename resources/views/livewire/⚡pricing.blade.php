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
    class="min-h-screen p-t-14"
    style="background: linear-gradient(180deg, var(--color-bg-dark-start), var(--color-bg-dark-end)); color: var(--color-text-primary); font-family: var(--font-sans);"
>
    {{-- HERO --}}
    <section class="baze-wrap pt-24 pb-16 text-center">
        <p class="uppercase tracking-[0.3em] text-xs font-semibold mb-5" style="color: var(--color-frequency);">
            Choose your signal
        </p>
        <h1 class="font-bold leading-[0.95] mb-6" style="font-family: var(--font-display); font-size: clamp(2.75rem, 7vw, 5.5rem); letter-spacing: 0.01em;">
            One deck.<br>
            <span x-text="isCreator ? 'Two ways to' : 'Two ways to'"></span>
            <span :style="isCreator ? 'color: var(--color-sunburst)' : 'color: var(--color-frequency)'" x-text="isCreator ? 'get paid.' : 'get in.'"></span>
        </h1>
        <p class="max-w-xl mx-auto text-base md:text-lg" style="color: var(--color-text-secondary); line-height: 1.65;">
            Slide the fader to switch between listening plans and creator plans —
            same platform, two different rooms.
        </p>
    </section>

    {{-- FADER TOGGLE --}}
    <section class="baze-wrap pb-20">
        <div class="max-w-md mx-auto">
            <div class="flex justify-between text-xs font-semibold uppercase tracking-widest mb-4">
                <button
                    type="button"
                    @click="mode = 'listener'"
                    :style="!isCreator ? 'color: var(--color-frequency)' : 'color: var(--color-text-faint)'"
                    class="transition-colors"
                >
                    Listener
                </button>
                <button
                    type="button"
                    @click="mode = 'creator'"
                    :style="isCreator ? 'color: var(--color-sunburst)' : 'color: var(--color-text-faint)'"
                    class="transition-colors"
                >
                    Creator
                </button>
            </div>

            <div
                x-ref="faderTrack"
                @click="drag($event)"
                class="relative h-12 rounded-full cursor-pointer select-none"
                style="background: var(--color-bg-card-start); border: 1px solid var(--color-border);"
                role="slider"
                aria-label="Toggle between listener and creator pricing"
                :aria-valuenow="isCreator ? 100 : 0"
                aria-valuemin="0"
                aria-valuemax="100"
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
    </section>

    {{-- PLAN GRID --}}
    <section class="baze-wrap pb-16">

        {{-- Listener plans --}}
        <div
            x-show="!isCreator"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div class="flex flex-wrap justify-center gap-8">
                @foreach ($listenerPlans as $plan)
                    <div class="card flex flex-col w-full sm:w-[46%] lg:w-[18%] lg:min-w-[200px]">
                        @if (! empty($plan['badge']))
                            <span class="self-start mb-3 px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wide"
                                  style="background: var(--color-frequency); color: #06161f;">
                                {{ $plan['badge'] }}
                            </span>
                        @endif

                        <div class="uppercase text-sm font-semibold mb-1" style="color: var(--color-text-secondary);">
                            {{ $plan['title'] }}
                        </div>

                        <div class="mb-1" style="font-family: var(--font-display); font-size: var(--font-size-4xl); letter-spacing: 0.02em;">
                            <span class="align-top text-sm mr-1" style="color: var(--color-text-muted);">KES</span>{{ number_format($plan['price']) }}
                        </div>

                        <div class="text-xs mb-6" style="color: var(--color-frequency);">
                            {{ $plan['downloads'] }}
                        </div>

                        <ul class="mb-8 space-y-2 text-sm flex-1" style="color: var(--color-text-secondary);">
                            @foreach ($plan['features'] as $feature)
                                <li class="flex gap-2">
                                    <span style="color: var(--color-frequency);">/</span>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>

                        @auth
                            <form action="{{ route('subscribe.pay') }}" method="POST">
                                @csrf
                                <input type="hidden" name="account_no" value="{{ auth()->user()->account_no }}">
                                <input type="hidden" name="subscription_id" value="premium">
                                <input type="hidden" name="plan" value="{{ $plan['title'] }}">
                                <input type="hidden" name="amount" value="{{ $plan['price'] }}">
                                <button type="submit" class="btn btn-primary w-full">Subscribe</button>
                            </form>
                        @else
                            <a href="{{ route('register', ['plan' => $plan['key'], 'mode' => 'listener']) }}" class="btn btn-primary w-full">
                                Create account
                            </a>
                        @endauth
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Creator plans --}}
        <div
            x-show="isCreator"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div class="flex flex-wrap justify-center gap-8">
                @foreach ($creatorPlans as $plan)
                    <div class="card flex flex-col w-full sm:w-[46%] lg:w-[18%] lg:min-w-[200px]">
                        @if (! empty($plan['badge']))
                            <span class="self-start mb-3 px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wide"
                                  style="background: var(--color-mustard); color: #06161f;">
                                {{ $plan['badge'] }}
                            </span>
                        @endif

                        <div class="uppercase text-sm font-semibold mb-1" style="color: var(--color-text-secondary);">
                            {{ $plan['title'] }}
                        </div>

                        <div class="mb-1" style="font-family: var(--font-display); font-size: var(--font-size-4xl); letter-spacing: 0.02em;">
                            <span class="align-top text-sm mr-1" style="color: var(--color-text-muted);">KES</span>{{ number_format($plan['price']) }}
                        </div>

                        <div class="text-xs mb-6" style="color: var(--color-sunburst);">
                            Billed yearly
                        </div>

                        <ul class="mb-8 space-y-2 text-sm flex-1" style="color: var(--color-text-secondary);">
                            @foreach ($plan['features'] as $feature)
                                <li class="flex gap-2">
                                    <span style="color: var(--color-sunburst);">/</span>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>

                        @auth
                            <form action="{{ route('subscribe.pay') }}" method="POST">
                                @csrf
                                <input type="hidden" name="account_no" value="{{ auth()->user()->account_no }}">
                                <input type="hidden" name="subscription_id" value="1">
                                <input type="hidden" name="plan" value="{{ $plan['title'] }}">
                                <input type="hidden" name="amount" value="{{ $plan['price'] }}">
                                <button type="submit" class="btn btn-primary w-full">Pay now</button>
                            </form>
                        @else
                            <a href="{{ route('register', ['plan' => $plan['key'], 'mode' => 'creator']) }}" class="btn btn-primary w-full">
                                Create account
                            </a>
                        @endauth
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FLASH MESSAGES --}}
    @if (session('success') || session('error'))
        <section class="baze-wrap pb-12">
            <div class="max-w-xl mx-auto text-center text-sm rounded-lg px-4 py-3"
                 style="background: var(--color-bg-card-start); border: 1px solid var(--color-border); color: {{ session('success') ? 'var(--color-frequency)' : 'var(--color-hibiscus)' }};">
                {{ session('success') ?? session('error') }}
            </div>
        </section>
    @endif

    {{-- WHY IT MATTERS --}}
    <section class="baze-wrap pb-24">
        <div class="baze-section-head" style="margin-bottom: 40px;">
            <h3 class="baze-display baze-h3" style="text-align:center;">
                Why the yearly plan pays off
            </h3>
        </div>

        <ul class="max-w-2xl mx-auto space-y-6 text-sm" style="color: var(--color-text-secondary);">
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
    </section>
</div>
