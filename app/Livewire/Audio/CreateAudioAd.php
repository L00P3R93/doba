<?php

namespace App\Livewire\Audio;

use App\Enums\AdTargetLevel;
use App\Http\Requests\Advertising\StoreAudioAdRequest;
use App\Models\AudioAd;
use App\Models\County;
use App\Models\SubCounty;
use App\Models\Ward;
use App\Services\Advertising\BannerPricingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateAudioAd extends Component
{
    use WithFileUploads;

    public string $level = 'general';

    public string $advertiser = '';

    public string $headline = '';

    public string $ctaText = '';

    public string $ctaUrl = '';

    public float $basePricePerImpression = 0.20;

    public float $budget = 0;

    public ?int $countyId = null;

    public ?int $subCountyId = null;

    public ?int $wardId = null;

    public $audioFile = null;

    public $bannerImage = null;

    #[Computed]
    public function counties()
    {
        return County::orderBy('name')->get();
    }

    #[Computed]
    public function subCounties()
    {
        if (! $this->countyId) {
            return collect();
        }

        return SubCounty::where('county_id', $this->countyId)->orderBy('name')->get();
    }

    #[Computed]
    public function wards()
    {
        if (! $this->subCountyId) {
            return collect();
        }

        return Ward::where('sub_county_id', $this->subCountyId)->orderBy('name')->get();
    }

    #[Computed]
    public function priceTiers(): array
    {
        return config('advertising.price_tiers', [0.20, 0.30, 0.40]);
    }

    #[Computed]
    public function pricingPreview()
    {
        $pricingService = new BannerPricingService;
        $county = $this->countyId ? County::find($this->countyId) : null;

        return $pricingService->calculate(
            level: $this->level,
            basePricePerImpression: $this->basePricePerImpression,
            budget: $this->budget,
            county: $county,
        );
    }

    public function updatedCountyId(): void
    {
        $this->subCountyId = null;
        $this->wardId = null;
    }

    public function updatedSubCountyId(): void
    {
        $this->wardId = null;
    }

    public function setAdType(string $adType, ?string $price = null): void
    {
        $this->reset();

        match ($adType) {
            'audio' => $this->level = 'county',
            default => $this->level = 'general',
        };

        if ($price !== null) {
            $this->basePricePerImpression = (float) $price;
        }
    }

    public function save()
    {
        $request = new StoreAudioAdRequest;
        $validated = $this->validate($request->rules());

        $pricingService = new BannerPricingService;
        $county = $this->countyId ? County::find($this->countyId) : null;

        $pricingResult = $pricingService->calculate(
            level: $this->level,
            basePricePerImpression: $this->basePricePerImpression,
            budget: $this->budget,
            county: $county,
        );

        // Handle file uploads
        $audioPath = null;
        $bannerImagePath = null;

        if ($this->audioFile) {
            $filename = 'audio_'.uniqid().'.'.$this->audioFile->getClientOriginalExtension();
            $this->audioFile->storeAs('audio-uploads', $filename, 'public');
            $audioPath = '/storage/audio-uploads/'.$filename;
        }

        if ($this->bannerImage) {
            $filename = 'banner_'.uniqid().'.'.$this->bannerImage->getClientOriginalExtension();
            $this->bannerImage->storeAs('banner-uploads', $filename, 'public');
            $bannerImagePath = '/storage/banner-uploads/'.$filename;
        }

        // Get target UID based on level
        $targetUid = null;
        if ($this->level === 'county' && $this->countyId) {
            $countyObj = County::find($this->countyId);
            $targetUid = $countyObj->uid ?? null;
        } elseif ($this->level === 'subcounty' && $this->subCountyId) {
            $subCountyObj = SubCounty::find($this->subCountyId);
            $countyObj = $subCountyObj->county;
            $targetUid = $subCountyObj->uid ?? null;
        } elseif ($this->level === 'ward' && $this->wardId) {
            $wardObj = Ward::find($this->wardId);
            $targetUid = $wardObj->uid ?? null;
        }

        $audioAd = AudioAd::create([
            'uuid' => Str::uuid(),
            'user_id' => Auth::id(),
            'advertiser' => $this->advertiser,
            'headline' => $this->headline,
            'cta_text' => $this->ctaText,
            'cta_url' => $this->ctaUrl,
            'audio_url' => $audioPath,
            'banner_image' => $bannerImagePath,
            'is_active' => false,
            'priority' => 1,
            'daily_limit' => null,
            'impressions' => 0,
            'target_level' => $this->level,
            'target_uid' => $targetUid,
            'clicks' => 0,
            'completions' => 0,
            'skips' => 0,
        ]);

        $this->reset();

        session()->flash('success', "Audio ad saved! Final price: KES {$pricingResult->finalPricePerImpression} | Max impressions: {$pricingResult->maxImpressions}");

        $this->dispatch('closeAudioModal');
    }

    public function render(): View
    {
        return view('livewire.audio.create-audio-ad');
    }
}