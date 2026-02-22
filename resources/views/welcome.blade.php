<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>DobaPlay - Earn as an Artist</title>

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Bootstrap -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
            rel="stylesheet"
        />
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        />

        <link rel="stylesheet" href="/styles.css" />
    </head>
    <body>
        <nav class="navbar navbar-expand-lg fixed-top">
            <div class="container">
                <a class="navbar-brand fw-bold text-white" href="{{ route('home') }}">
                    Doba<span>Play</span>
                </a>

                <button
                    class="navbar-toggler text-white"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navMenu"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navMenu">
                    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
                        @if (Route::has('login'))
                            @auth
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="nav-link">Log Out</button>
                                    </form>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                                    </li>
                                @endif
                            @endauth
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="#">Premium</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">Ads</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">Events</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">Jobs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- INTRO -->
        <section class="hero">
            <div class="container">
                <h1 class="fw-bold">
                    Turn Your Music Into <span>Income</span>
                </h1>
                <p>
                    Monetize your music and videos as an artist, producer, studio, or
                    record label. Upload, distribute, and get paid — yearly plans, no stress.
                </p>
            </div>
        </section>

        <!-- PRICING -->
        <section class="pb-5">
            <div class="container">
                <div class="row g-4 align-items-stretch justify-content-center">
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
                        <div class="col-md-3">
                            <div class="pricing-card d-flex flex-column">
                                <div>
                                    <div class="plan-title">{{ $plan['title'] }}</div>
                                    <div class="price"><span class="currency">KES</span> {{ $plan['price'] }}</div>
                                    <div class="billing">BILLED YEARLY</div>

                                    <ul class="features">
                                        @foreach($plan['features'] as $feature)
                                            <li>{{ $feature }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <a href="{{ route('register') }}" class="btn btn-gold w-100 mt-auto">CREATE ACCOUNT</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="py-5">
            <div class="container">
                <h3 class="text-center mb-4 fw-bold">
                    Why This Yearly Payment Matters
                </h3>

                <ul class="awesome-list mx-auto">
                    <li>
                        <i class="fa-solid fa-database"></i>
                        Secure storage for music and video uploads
                    </li>
                    <li>
                        <i class="fa-solid fa-gauge-high"></i>
                        High-speed streaming & bandwidth performance
                    </li>
                    <li>
                        <i class="fa-solid fa-shield-halved"></i>
                        Platform security & content protection
                    </li>
                    <li>
                        <i class="fa-solid fa-copyright"></i>
                        Copyright detection to protect original music and prevent unauthorized uploads
                    </li>
                    <li>
                        <i class="fa-solid fa-money-bill-transfer"></i>
                        Artist payouts, transaction fees, and payment processing infrastructure
                    </li>
                </ul>
            </div>
        </section>

        <footer>
            © {{ date('Y') }} DobaPlay. All rights reserved.
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
