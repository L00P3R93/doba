<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Fonts: Bebas Neue (display) + Manrope (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome (icons used in the brand-panel feature list) -->
    <link  rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    {{-- NEW: replaces the old {{ $title ?? '...' }} line --}}
    <x-seo-meta
        :title="$title ?? null"
        :description="$metaDescription ?? null"
        :image="$metaImage ?? null"
        :canonical="$canonical ?? null"
        :type="$ogType ?? 'website'"
        :noindex="$noindex ?? false"
        :json-ld="$jsonLd ?? null"
    />

    {{-- Sitewide structured data — every marketing page gets this once --}}
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'DobaPlay',
            'url' => url('/'),
            'logo' => asset('favicon-32x32.png'),
            'description' => "East Africa's music and video distribution and monetisation platform for artists, studios, record labels, events, and filmmakers.",
            'areaServed' => 'KE',
            'sameAs' => [
                // add your real social profile URLs here, e.g.:
                // 'https://www.instagram.com/dobaplay',
                // 'https://twitter.com/dobaplay',
                // 'https://www.facebook.com/dobaplay',
            ],
        ], JSON_UNESCAPED_SLASHES) !!}
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased" style="background:var(--color-ink); color:var(--color-bone);">

@include('components.baze-navbar')

<main id="main-content">
    {{ $slot }}
</main>

<footer class="baze-footer">
    <div class="baze-wrap baze-footer-grid">
        <div class="baze-display baze-footer-logo">Doba<span class="baze-accent">Play</span></div>
        <div class="baze-footer-links">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('pricing') }}">Pricing</a>
            <a href="{{ route('advertise') }}">Advertise</a>
            <a href="{{ url('/support') }}">Support</a>
            <a href="{{ url('/terms') }}">Terms</a>
            <a href="{{ url('/privacy') }}">Privacy</a>
        </div>
    </div>
    <div class="baze-wrap baze-footer-bottom">
        <span>&copy; {{ date('Y') }} DobaPlay. All rights reserved.</span>
        <span>Made for artists, studios &amp; labels across East Africa.</span>
    </div>
</footer>

@livewireScripts
@fluxScripts
</body>
</html>
