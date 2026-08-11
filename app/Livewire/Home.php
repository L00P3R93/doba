<?php

namespace App\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('DobaPlay - Turn Your Sound Into Income')]
#[Layout('layouts.marketing', [
    'metaDescription' => 'Upload once, get paid everywhere. DobaPlay is East Africa\'s distribution home for artists, studios, and record labels — one yearly plan, Instant payouts, zero upload fees.',
    'metaImage' => 'og/home-og.png',
    'keywords' => 'doba, dobaplay, doba play, dobaplay kenya, dobaplay login, dobaplay register, dobaplay sign up, dobaplay pricing, dobaplay app, dobaplay artist account, is dobaplay legit, music distribution kenya, music distribution east africa, upload music online kenya, platform for musicians in kenya, music royalties payout m-pesa',
    'jsonLd' => null, // Organization schema already covers the homepage sitewide
])]
class Home extends Component
{
    /**
     * "The Crate" — creator plans, in display order.
     * Each entry drives one record-card in the pricing rail.
     */
    public array $plans = [
        [
            'key' => 'events',
            'title' => 'EVENTS',
            'price' => '499',
            'icon' => 'event.png',
            'spine' => 'freq',
            'cta_bg' => 'var(--color-frequency)',
            'cta_text' => 'var(--color-ink)',
            'popular' => false,
            'features' => [
                'Sell tickets online',
                'Promote your event',
                'Reach the right audience',
                'Merch store integration',
            ],
        ],
        [
            'key' => 'artist_video',
            'title' => 'ARTIST + VIDEOS',
            'price' => '1,499',
            'icon' => 'music.png',
            'spine' => 'sun',
            'cta_bg' => 'var(--color-sunburst)',
            'cta_text' => 'var(--color-ink)',
            'popular' => true,
            'features' => [
                'Music & video uploads',
                'Streaming earnings',
                'Video monetization',
                'Artist analytics',
                'Promote events',
                'Merch store integration',
            ],
        ],
        [
            'key' => 'studio',
            'title' => 'STUDIO',
            'price' => '14,999',
            'icon' => 'studio.png',
            'spine' => 'mus',
            'cta_bg' => 'var(--color-mustard)',
            'cta_text' => 'var(--color-ink)',
            'popular' => false,
            'features' => [
                '10 artist accounts',
                'Music & video uploads',
                'Studio dashboard',
                'Promote events',
                'Sell beats & get producing gigs',
            ],
        ],
        [
            'key' => 'record_label',
            'title' => 'RECORD LABEL',
            'price' => '49,999',
            'icon' => 'record.png',
            'spine' => 'bone',
            'cta_bg' => 'var(--color-bone)',
            'cta_text' => 'var(--color-ink)',
            'popular' => false,
            'features' => [
                'Unlimited artists',
                'All studio features',
                'Label payouts & analytics',
                'Promote events',
            ],
        ],
        [
            'key' => 'cinema',
            'title' => 'CINEMA',
            'price' => '3,999',
            'icon' => 'cinema-2.png',
            'spine' => 'hib',
            'cta_bg' => 'var(--color-hibiscus)',
            'cta_text' => 'var(--color-bone)',
            'popular' => false,
            'features' => [
                'VOD distribution',
                'Pay-per-view & rentals',
                'Subtitle support',
                'Premiere promotion',
            ],
        ],
    ];

    /**
     * "The Signal Chain" — feature strip explaining why the yearly plan matters.
     */
    public array $signalChain = [
        [
            'title' => 'SECURE STORAGE',
            'body' => 'Your masters and video files, backed up and protected.',
            'icon' => 'storage',
        ],
        [
            'title' => 'FAST STREAMING',
            'body' => 'Low-latency delivery built for East African networks.',
            'icon' => 'bolt',
        ],
        [
            'title' => 'PLATFORM SECURITY',
            'body' => 'Account protection and content safeguards, always on.',
            'icon' => 'shield',
        ],
        [
            'title' => 'COPYRIGHT CHECKS',
            'body' => 'Automatic detection keeps your originals protected.',
            'icon' => 'copyright',
        ],
        [
            'title' => 'M-PESA PAYOUTS',
            'body' => 'Earnings land in your pocket, no waiting on invoices.',
            'icon' => 'wallet',
        ],
    ];

    public function render(): Factory|View|\Illuminate\View\View
    {
        return view('livewire.⚡home');
    }
}
