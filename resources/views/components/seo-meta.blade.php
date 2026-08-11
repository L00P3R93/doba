{{--
    resources/views/components/seo-meta.blade.php

    Drop-in SEO component. Include once in the <head> of every layout
    (marketing.blade.php AND the auth "simple" layout that uses head.blade.php).

    Usage from a layout:
        <x-seo-meta
            :title="$title ?? null"
            :description="$metaDescription ?? null"
            :image="$metaImage ?? null"
            :canonical="$canonical ?? null"
            :type="$ogType ?? 'website'"
            :noindex="$noindex ?? false"
            :json-ld="$jsonLd ?? null"
        />

    $title / $metaDescription / $metaImage / $canonical / $jsonLd are supplied
    per-page via the Livewire #[Layout('layouts.marketing', [...])] params
    (see the guide for exact per-page values).
--}}
@props([
    'title' => null,
    'description' => "DobaPlay is East Africa's music and video distribution platform for artists, studios, record labels, event promoters and filmmakers. Upload once, get paid everywhere with M-Pesa payouts and zero upload fees.",
    'keywords' => null,
    'image' => null,
    'canonical' => null,
    'type' => 'website',
    'noindex' => false,
    'jsonLd' => null,
])

@php
    $siteName = 'DobaPlay';

    $resolvedTitle = $title
        ? (str_contains($title, 'DobaPlay') ? $title : "{$title} | {$siteName}")
        : "{$siteName} — Music & Video Distribution for East African Artists";

    // Always strip query strings from the canonical unless one is explicitly passed.
    // This prevents /pricing?mode=creator and /pricing?mode=listener from being
    // treated as separate pages by search engines.
    $resolvedCanonical = $canonical ?? url(request()->path());

    $resolvedImage = $image
        ? (str_starts_with($image, 'http') ? $image : asset($image))
        : asset('og/default-og.jpg');
@endphp

{{-- Primary --}}
<title>{{ $resolvedTitle }}</title>
<meta name="description" content="{{ $description }}">
@if ($keywords)
    <meta name="keywords" content="{{ $keywords }}">
@endif
<meta name="author" content="DobaPlay">
<meta name="robots" content="{{ $noindex ? 'noindex, nofollow' : 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' }}">
<link rel="canonical" href="{{ $resolvedCanonical }}">

{{-- Open Graph --}}
<meta property="og:type" content="{{ $type }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $resolvedTitle }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $resolvedCanonical }}">
<meta property="og:image" content="{{ $resolvedImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="{{ $resolvedTitle }}">
<meta property="og:locale" content="en_KE">

{{-- Twitter / X --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $resolvedTitle }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $resolvedImage }}">

{{-- Theme / PWA polish (helps rich mobile SERP + install prompts) --}}
<meta name="theme-color" content="#120F0D">
<meta name="apple-mobile-web-app-title" content="{{ $siteName }}">
<meta name="format-detection" content="telephone=no">

{{-- Per-page structured data (Product, WebPage, BreadcrumbList, etc.) --}}
@if ($jsonLd)
    <script type="application/ld+json">{!! $jsonLd !!}</script>
@endif
