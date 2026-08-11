<?php

namespace App\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Advertise | DobaPlay')]
#[Layout('layouts.marketing', [
    'metaDescription' => 'Reach real listeners across East Africa. Banner, audio, interstitial, and rewarded ad formats on DobaPlay, targetable from national level down to county and ward.',
    'metaImage' => 'og/advertise-og.png',
    'keywords' => 'advertise on music streaming app, audio advertising kenya, targeted ads by county kenya, reach music listeners kenya, banner ads music platform',
])]
class Advertise extends Component
{
    /**
     * Ad formats. 'modal' is the Alpine modal group this card opens —
     * banner and audio each get a dedicated modal (fixed type/price,
     * so no runtime bridge needed); interstitial and rewarded share the
     * "video" modal because they both feed the same create-video-ad form,
     * so their type/price get dispatched at open-time instead.
     */
    public array $adTypes = [
        [
            'key' => 'banner',
            'modal' => 'banner',
            'title' => 'Banner Ads',
            'price' => 0.2,
            'billing' => 'per impression',
            'features' => [
                'Visible on cover art',
                'Visible in song sections',
                'Clickable call-to-action',
                'Custom creative support',
                'County to ward targeting',
            ],
        ],
        [
            'key' => 'audio',
            'modal' => 'audio',
            'title' => 'Audio Ads',
            'price' => 1,
            'billing' => 'per completion',
            'features' => [
                'Plays between tracks',
                'No screen required',
                'Buying real listening time',
            ],
        ],
        [
            'key' => 'interstitial',
            'modal' => 'video',
            'title' => 'Interstitial Ads',
            'price' => 1.5,
            'billing' => 'per completion',
            'badge' => 'High visibility',
            'features' => [
                'Full-screen ad placements',
                'Integrated into video playback',
                'Optional skip after 10 seconds',
                'High visibility & click rates',
                'Customizable timing & frequency',
            ],
        ],
        [
            'key' => 'rewarded',
            'modal' => 'video',
            'title' => 'Rewarded Ads',
            'price' => 2,
            'billing' => 'per completion',
            'badge' => 'Best engagement',
            'features' => [
                'Users watch to earn rewards',
                'Plays between tracks',
                'High visibility & click rates',
                'High engagement & retention',
                'Great for free download & music content',
            ],
        ],
    ];

    public function render(): Factory|\Illuminate\Contracts\View\View|View
    {
        return view('livewire.⚡advertise');
    }
}
