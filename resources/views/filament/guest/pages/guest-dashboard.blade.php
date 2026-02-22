<x-filament-panels::page class="p-0!">

    <div class="fixed inset-0 -z-10 bg-linear-to-b from-[#081c25] to-black"></div>

    <div class="w-full px-6 py-20">
        <div class="max-w-6xl mx-auto text-center">

            <!-- PAGE TITLE -->
            <h1 class="text-4xl md:text-5xl font-bold text-white">
                Turn Your Music Into
                <span class="text-yellow-400">Income</span>
            </h1>

            <p class="mt-6 text-lg text-gray-300 max-w-3xl mx-auto">
                Monetize your music and videos as an artist, producer, studio,
                or record label. Upload, distribute, and get paid — yearly plans, no stress.
            </p>

            <!-- PRICING GRID -->
            <div class="mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                @php
                    $plans = [
                        [
                            'title' => 'EVENTS',
                            'price' => '499',
                            'features' => [
                                'Sell tickets',
                                'Promote Events',
                                'Reach the right audience',
                                'Merchandise store integration',
                            ],
                        ],
                        [
                            'title' => 'ARTIST + VIDEOS',
                            'price' => '1,499',
                            'features' => [
                                'Music & video uploads',
                                'Streaming earnings',
                                'Video monetization',
                                'Artist analytics',
                                'Promote Events',
                                'Merchandise store integration',
                            ],
                        ],
                        [
                            'title' => 'STUDIO',
                            'price' => '14,999',
                            'features' => [
                                '10 artist accounts',
                                'Music & video uploads',
                                'Studio dashboard',
                                'Promote Events',
                                'Merchandise store integration',
                                'Sell beats & get producing gigs',
                            ],
                        ],
                        [
                            'title' => 'RECORD LABEL',
                            'price' => '49,999',
                            'features' => [
                                'Unlimited artists',
                                'Promote Events',
                                'All studio features',
                                'Label payouts & analytics',
                                'Merchandise store integration',
                                'Sell beats & get producing gigs',
                            ],
                        ],
                    ];
                @endphp

                @foreach($plans as $plan)
                    <div class="bg-[#0f2f3a] text-white rounded-2xl p-8 shadow-xl
                                transition hover:-translate-y-2 hover:shadow-2xl
                                flex flex-col justify-between">

                        <div>
                            <h3 class="text-sm tracking-widest text-gray-300 font-semibold">
                                {{ $plan['title'] }}
                            </h3>

                            <div class="mt-6">
                                <div class="text-sm text-gray-400">KES</div>
                                <div class="text-5xl font-bold">
                                    {{ $plan['price'] }}
                                </div>
                                <div class="text-xs text-gray-400 mt-2 tracking-wider">
                                    BILLED YEARLY
                                </div>
                            </div>

                            <ul class="mt-8 space-y-4 text-sm text-gray-300">
                                @foreach($plan['features'] as $feature)
                                    <li class="flex items-start gap-3">
                                        <x-filament::icon
                                            icon="heroicon-m-check"
                                            class="w-5 h-5 text-yellow-400 mt-0.5"
                                        />
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <a href="{{ route('register') }}"
                           class="mt-10 block w-full text-center bg-yellow-400
                                  text-black font-semibold py-3 rounded-lg
                                  hover:bg-yellow-300 transition">
                            CREATE ACCOUNT
                        </a>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

</x-filament-panels::page>
