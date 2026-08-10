<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'DobaPlay - Earn as an Artist' }}</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Fonts: Bebas Neue (display) + Manrope (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
            <a href="{{ url('/#plans') }}">Creator Plans</a>
            <a href="{{ route('pricing') }}">Listener Plans</a>
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
