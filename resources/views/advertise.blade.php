<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DobaPlay - Advertise With Us</title>

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
@include('components.navbar')
<!-- HERO -->
<section class="hero">
    <div class="container">
        <h1 class="fw-bold">
            Advertise With Doba<span>Play</span>
        </h1>
        <p>
            Reach thousands of music fans, listeners, and creators through our banner, audio, rewarded, and interstitial ads, with granular targeting from national level down to counties, sub-counties, and wards. Flexible plans for every budget.
        </p>
    </div>
</section>

<!-- AD TYPES -->
<section class="pb-5">
    <div class="container">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row g-4 align-items-stretch justify-content-center">
            @php
                $adTypes = [
                    [
                        'title' => 'BANNER ADS',
                        'price' => '0.2',
                        'billing' => 'PER IMPRESSION',
                        'href' => 'banners.php',
                        'features' => [
                            'Visible on cover arts',
                            'Visible song sections',
                            'Clickable call-to-action',
                            'Custom creative support',
                        ],
                    ],
                    [
                        'title' => 'AUDIO ADS',
                        'price' => '1',
                        'billing' => 'PER COMPLETION',
                        'href' => 'audio_ads.php',
                        'features' => [
                            'Play between tracks',
                            'No screen required',
                            'Buying real listening time',
                        ],
                    ],
                    [
                        'title' => 'INTERSTITIAL ADS',
                        'price' => '1.5',
                        'billing' => 'PER COMPLETION',
                        'href' => 'interstitial.php',
                        'features' => [
                            'Full-screen ad placements',
                            'Integrated directly into video playback',
                            'Optional skip after 10 seconds',
                            'High visibility & click rates',
                            'Customizable timing & frequency',
                        ],
                    ],
                    [
                        'title' => 'REWARDED ADS',
                        'price' => '2',
                        'billing' => 'PER COMPLETION',
                        'href' => 'rewarded.php',
                        'features' => [
                            'Users watch to earn rewards',
                            'Play between tracks',
                            'High visibility & click rates',
                            'High engagement & retention',
                            'Perfect for free download & music content',
                        ],
                    ],
                ];
            @endphp

            @foreach($adTypes as $ad)
                <div class="col-md-3">
                    <div class="pricing-card d-flex flex-column">
                        <div>
                            <div class="plan-title">{{ $ad['title'] }}</div>
                            <div class="price"><span class="currency">KES</span> {{ $ad['price'] }}</div>
                            <div class="billing">{{ $ad['billing'] }}</div>

                            <ul class="features">
                                @foreach($ad['features'] as $feature)
                                    <li>{{ $feature }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <a class="btn btn-gold w-100 mt-auto" href="#">BUY NOW</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- WHY ADVERTISE -->
<section class="py-5">
    <div class="container">
        <h1 class="text-center mb-4 fw-bold">
            Why Advertise With Doba<span>Play</span>
        </h1>

        <ul class="awesome-list mx-auto">
            <li>
                <i class="fa-solid fa-bullhorn"></i>
                Reach a highly engaged music audience across our platform
            </li>
            <li>
                <i class="fa-solid fa-chart-line"></i>
                Track ad performance and maximize ROI with analytics
            </li>
            <li>
                <i class="fa-solid fa-shield-halved"></i>
                Safe and secure ad placements
            </li>
            <li>
                <i class="fa-solid fa-clock"></i>
                Flexible campaigns tailored to your schedule and budget
            </li>

            <li>
                <i class="fa-solid fa-users"></i>
                Targeted campaigns for your audience
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
