<?php

namespace App\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Pricing — Listener & Creator Plans | DobaPlay')]
class Pricing extends Component
{
    /**
     * Which side of the fader is active on load.
     * Synced to the URL (?mode=listener|creator) so /premium and /subscribe
     * can redirect here with the right default, and links stay shareable.
     */
    #[Url(as: 'mode', history: true)]
    public string $mode = 'listener';

    /**
     * Listener (premium) plans — ad-free streaming for consumers.
     */
    public array $listenerPlans = [
        [
            'key' => 'week',
            'title' => '1 Week',
            'price' => 50,
            'downloads' => '150 downloads',
            'features' => [
                'Free listening',
                'No ads',
                'Full access to all songs',
                'Priority access to new releases',
            ],
        ],
        [
            'key' => 'month',
            'title' => '1 Month',
            'price' => 180,
            'downloads' => '900 downloads',
            'features' => [
                'Free listening',
                'No ads',
                'Full access to all songs',
                'Priority access to new releases',
            ],
        ],
        [
            'key' => 'quarter',
            'title' => '3 Months',
            'price' => 500,
            'downloads' => '3,000 downloads',
            'features' => [
                'Free listening',
                'No ads',
                'Full access to all songs',
                'Priority access to new releases',
            ],
        ],
        [
            'key' => 'half-year',
            'title' => '6 Months',
            'price' => 950,
            'downloads' => '7,500 downloads',
            'features' => [
                'Free listening',
                'No ads',
                'Full access to all songs',
                'Priority access to new releases',
            ],
        ],
        [
            'key' => 'year',
            'title' => '1 Year',
            'price' => 1800,
            'downloads' => '18,000 downloads',
            'badge' => 'Best value',
            'features' => [
                'Free listening',
                'No ads',
                'Full access to all songs',
                'Priority access to new releases',
            ],
        ],
    ];

    /**
     * Creator (yearly) plans — matches the set on the Home page exactly
     * (including Cinema) so the two pages never drift out of sync again.
     */
    public array $creatorPlans = [
        [
            'key' => 'events',
            'title' => 'Events',
            'price' => 499,
            'features' => [
                'Sell tickets',
                'Promote events',
                'Reach the right audience',
                'Merch store integration',
            ],
        ],
        [
            'key' => 'artist-video',
            'title' => 'Artist + Videos',
            'price' => 1499,
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
            'key' => 'cinema',
            'title' => 'Cinema',
            'price' => 3999,
            'badge' => 'For movie makers',
            'features' => [
                'VOD distribution',
                'Pay-per-view & rental pricing',
                'Subtitle support',
                'Premiere promotion',
            ],
        ],
        [
            'key' => 'studio',
            'title' => 'Studio',
            'price' => 14999,
            'features' => [
                '10 artist accounts',
                'Music & video uploads',
                'Studio dashboard',
                'Promote events',
                'Merch store integration',
                'Sell beats & get producing gigs',
            ],
        ],
        [
            'key' => 'label',
            'title' => 'Record Label',
            'price' => 49999,
            'features' => [
                'Unlimited artists',
                'Promote events',
                'All studio features',
                'Label payouts & analytics',
                'Merch store integration',
                'Sell beats & get producing gigs',
            ],
        ],
    ];

    public function mount(): void
    {
        if (! in_array($this->mode, ['listener', 'creator'], true)) {
            $this->mode = 'listener';
        }
    }

    public function setMode(string $mode): void
    {
        $this->mode = in_array($mode, ['listener', 'creator'], true) ? $mode : 'listener';
    }

    /**
     * Build schema.org Product/Offer JSON-LD from the plan arrays so
     * individual plans are eligible for Google price/rich results.
     * Assumes each plan has: title, price, key, features[] — matches
     * the fields already used in the Blade view (adjust field names
     * below if your actual array keys differ).
     */
    protected function buildPricingJsonLd(): string
    {
        $toOffers = function (array $plans, string $category) {
            return collect($plans)->map(function ($plan) use ($category) {
                return [
                    '@type' => 'Product',
                    'name' => "DobaPlay {$plan['title']} Plan",
                    'category' => $category,
                    'url' => route('pricing').'#'.($plan['key'] ?? Str::slug($plan['title'])),
                    'brand' => [
                        '@type' => 'Brand',
                        'name' => 'DobaPlay',
                    ],
                    'offers' => [
                        '@type' => 'Offer',
                        'price' => (string) $plan['price'],
                        'priceCurrency' => 'KES',
                        'availability' => 'https://schema.org/InStock',
                        'url' => route('pricing').'#'.($plan['key'] ?? Str::slug($plan['title'])),
                        'priceValidUntil' => now()->addYear()->toDateString(),
                    ],
                ];
            })->values()->all();
        };

        $items = array_merge(
            $toOffers($this->listenerPlans, 'Listener Subscription'),
            $toOffers($this->creatorPlans, 'Creator Distribution Plan'),
        );

        return json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'itemListElement' => collect($items)->map(function ($item, $i) {
                return [
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'item' => $item,
                ];
            })->values()->all(),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function render(): Factory|\Illuminate\Contracts\View\View|View
    {
        return view('livewire.⚡pricing')
            ->layout('layouts.marketing', [
                'metaDescription' => 'Premium music streaming plans for listeners and one yearly distribution plan for artists, studios, record labels, events, and filmmakers — all payable via M-Pesa.',
                'metaImage' => 'og/pricing-og.jpg',
                'keywords' => 'premium music streaming kenya, m-pesa music subscription, record label distribution platform africa, ad-free music streaming africa, offline music download app kenya',
                'jsonLd' => $this->buildPricingJsonLd(),
            ]);
    }
}
