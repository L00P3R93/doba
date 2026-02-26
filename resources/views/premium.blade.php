<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DobaPlay - Premium</title>

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
            Premium Users <span>Only</span>
        </h1>
        <p>
            Enjoy free listening with no ads. Choose your preferred subscription plan below.
        </p>
    </div>
</section>

<!-- PRICING -->
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
                $plans = [
                    [
                        'title' => '1 Week',
                        'price' => '50',
                        'downloads' => '150 Downloads',
                        'features' => [
                            'Free listening',
                            'No ads',
                            'Full access to all songs',
                            'Priority access to new releases',
                        ],
                    ],
                    [
                        'title' => '1 Month',
                        'price' => '180',
                        'downloads' => '900 Downloads',
                        'features' => [
                            'Free listening',
                            'No ads',
                            'Priority access to new releases',
                            'Full access to all songs',
                        ],
                    ],
                    [
                        'title' => '3 Months',
                        'price' => '500',
                        'downloads' => '3,000 Downloads',
                        'features' => [
                            'Free listening',
                            'No ads',
                            'Priority access to new releases',
                            'Full access to all songs',
                        ],
                    ],
                    [
                        'title' => '6 Months',
                        'price' => '950',
                        'downloads' => '7,500 Downloads',
                        'features' => [
                            'Free listening',
                            'No ads',
                            'Priority access to new releases',
                            'Full access to all songs',
                        ],
                    ],
                    [
                        'title' => '1 Year',
                        'price' => '1,800',
                        'downloads' => '18,000 Downloads',
                        'features' => [
                            'Free listening',
                            'No ads',
                            'Priority access to new releases',
                            'Full access to all songs',
                        ],
                    ],
                ];
            @endphp

            @foreach($plans as $key => $plan)
                <div class="col-md-2-4"> <!-- Custom class for 5 columns instead of 3 -->
                    <div class="pricing-card d-flex flex-column">
                        <div>
                            <div class="plan-title">{{ $plan['title'] }}</div>
                            <div class="price"><span class="currency">KES</span> {{ number_format((float)str_replace(',', '', $plan['price']), 0) }}</div>
                            <div class="billing">{{ $plan['downloads'] }}</div>

                            <ul class="features">
                                @foreach($plan['features'] as $feature)
                                    <li>{{ $feature }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @if (Route::has('login'))
                            @auth
                                <form action="{{ route('subscribe.pay') }}" method="POST" class="mt-auto">
                                    @csrf
                                    <input type="hidden" name="account_no" value="{{ auth()->user()->account_no }}">
                                    <input type="hidden" name="subscription_id" value="premium">
                                    <input type="hidden" name="plan" value="{{ $plan['title'] }}">
                                    <input type="hidden" name="amount" value="{{ str_replace(',', '', $plan['price']) }}">
                                    <button type="submit" class="btn btn-gold w-100">SUBSCRIBE</button>
                                </form>
                            @else
                                <a href="" class="btn-gold mt-auto">SUBSCRIBE</a>
                            @endauth
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<footer>
    © {{ date('Y') }} DobaPlay. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
