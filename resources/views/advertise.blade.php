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

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet"/>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
</head>
<body>
@include('components.navbar')
<!-- HERO -->
<section id="main-content" class="hero">
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
                        'type' => 'banner',
                        'features' => [
                            'Visible on cover arts',
                            'Visible song sections',
                            'Clickable call-to-action',
                            'Custom creative support',
                            'County to ward targeting',
                        ],
                    ],
                    [
                        'title' => 'AUDIO ADS',
                        'price' => '1',
                        'billing' => 'PER COMPLETION',
                        'type' => 'audio',
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
                        'type' => 'interstitial',
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
                        'type' => 'rewarded',
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

            @foreach($adTypes as $key => $ad)
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
                        @if($ad['type'] === 'banner')
                            <button class="btn btn-gold w-100 mt-auto" type="button" data-bs-toggle="modal" data-bs-target="#bannerAdModal" data-ad-type="{{ $ad['type'] }}" data-price="{{ $ad['price'] }}">
                                BUY NOW
                            </button>
                        @elseif($ad['type'] === 'audio')
                            <button class="btn btn-gold w-100 mt-auto" type="button" data-bs-toggle="modal" data-bs-target="#audioAdModal" data-ad-type="{{ $ad['type'] }}" data-price="{{ $ad['price'] }}">
                                BUY NOW
                            </button>
                        @elseif($ad['type'] === 'interstitial')
                            <button class="btn btn-gold w-100 mt-auto" type="button" data-bs-toggle="modal" data-bs-target="#videoAdModal" data-ad-type="{{ $ad['type'] }}" data-price="{{ $ad['price'] }}">
                                BUY NOW
                            </button>
                        @elseif($ad['type'] === 'rewarded')
                            <button class="btn btn-gold w-100 mt-auto" type="button" data-bs-toggle="modal" data-bs-target="#videoAdModal" data-ad-type="{{ $ad['type'] }}" data-price="{{ $ad['price'] }}">
                                BUY NOW
                            </button>
                        @else
                            <button class="btn btn-gold w-100 mt-auto" type="button" data-bs-toggle="modal" data-bs-target="#bannerAdModal" data-ad-type="{{ $ad['type'] }}" data-price="{{ $ad['price'] }}">
                                BUY NOW
                            </button>
                        @endif
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

<!-- Banner Ad Modal -->
<div class="modal fade" id="bannerAdModal" tabindex="-1" aria-labelledby="bannerAdModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: linear-gradient(180deg, #0f2d3f, #091c28); border-radius: 14px; box-shadow: 0 20px 40px rgba(0,0,0,0.4); border: none;">
            <div class="modal-header" style="background: rgba(15, 45, 63, 0.5); border-bottom: 1px solid rgba(240, 229, 84, 0.2);">
                <h5 class="modal-title fw-bold" id="bannerAdModalLabel" style="color: #f5c542;">
                    <i class="fas fa-bullhorn me-2"></i>Create Banner Ad
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                @livewire('ads.create-banner-ad')
            </div>
        </div>
    </div>
</div>

<!-- Audio Ad Modal -->
<div class="modal fade" id="audioAdModal" tabindex="-1" aria-labelledby="audioAdModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: linear-gradient(180deg, #0f2d3f, #091c28); border-radius: 14px; box-shadow: 0 20px 40px rgba(0,0,0,0.4); border: none;">
            <div class="modal-header" style="background: rgba(15, 45, 63, 0.5); border-bottom: 1px solid rgba(240, 229, 84, 0.2);">
                <h5 class="modal-title fw-bold" id="audioAdModalLabel" style="color: #f5c542;">
                    <i class="fas fa-volume-up me-2"></i>Create Audio Ad
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                @livewire('audio.create-audio-ad')
            </div>
        </div>
    </div>
</div>

<!-- Video Ad Modal -->
<div class="modal fade" id="videoAdModal" tabindex="-1" aria-labelledby="videoAdModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: linear-gradient(180deg, #0f2d3f, #091c28); border-radius: 14px; box-shadow: 0 20px 40px rgba(0,0,0,0.4); border: none;">
            <div class="modal-header" style="background: rgba(15, 45, 63, 0.5); border-bottom: 1px solid rgba(240, 229, 84, 0.2);">
                <h5 class="modal-title fw-bold" id="videoAdModalLabel" style="color: #f5c542;">
                    <i class="fas fa-video me-2"></i>Create Video Ad
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                @livewire('video.create-video-ad')
            </div>
        </div>
    </div>
</div>

<footer>
    © {{ date('Y') }} DobaPlay. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

@livewireScripts
<script>
    document.addEventListener('livewire:init', () => {
        // Handle modal opening with ad type autofill
        const bannerAdModal = document.getElementById('bannerAdModal');
        const audioAdModal = document.getElementById('audioAdModal');
        const videoAdModal = document.getElementById('videoAdModal');

        if (bannerAdModal) {
            bannerAdModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const adType = button.getAttribute('data-ad-type');
                const price = button.getAttribute('data-price');

                // Find the Livewire component inside this modal
                const modalLivewireEl = bannerAdModal.querySelector('[wire\\:id]');
                if (modalLivewireEl) {
                    const componentId = modalLivewireEl.getAttribute('wire:id');
                    // Get the Livewire component by its ID
                    if (window.Livewire && window.Livewire.components) {
                        const components = window.Livewire.components;
                        const component = components[componentId];
                        if (component) {
                            component.setAdType(adType, price);
                        }
                    }
                }
            });
        }

        if (audioAdModal) {
            audioAdModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const adType = button.getAttribute('data-ad-type');
                const price = button.getAttribute('data-price');

                // Find the Livewire component inside this modal
                const modalLivewireEl = audioAdModal.querySelector('[wire\\:id]');
                if (modalLivewireEl) {
                    const componentId = modalLivewireEl.getAttribute('wire:id');
                    // Get the Livewire component by its ID
                    if (window.Livewire && window.Livewire.components) {
                        const components = window.Livewire.components;
                        const component = components[componentId];
                        if (component) {
                            component.setAdType(adType, price);
                        }
                    }
                }
            });
        }

        if (videoAdModal) {
            videoAdModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const adType = button.getAttribute('data-ad-type');
                const price = button.getAttribute('data-price');

                // Find the Livewire component inside this modal
                const modalLivewireEl = videoAdModal.querySelector('[wire\\:id]');
                if (modalLivewireEl) {
                    const componentId = modalLivewireEl.getAttribute('wire:id');
                    // Get the Livewire component by its ID
                    if (window.Livewire && window.Livewire.components) {
                        const components = window.Livewire.components;
                        const component = components[componentId];
                        if (component) {
                            component.setAdType(adType, price);
                        }
                    }
                }
            });
        }

        // Handle modal closing events from Livewire components
        Livewire.on('closeBannerModal', () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('bannerAdModal'));
            if (modal) {
                modal.hide();
            }
        });

        Livewire.on('closeAudioModal', () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('audioAdModal'));
            if (modal) {
                modal.hide();
            }
        });

        Livewire.on('closeVideoModal', () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('videoAdModal'));
            if (modal) {
                modal.hide();
            }
        });
    });
</script>
</body>
</html>
