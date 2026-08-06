<?php

namespace App\Livewire\Ads;

use App\Enums\AdTargetLevel;
use App\Enums\BannerAdStatus;
use App\Http\Requests\Advertising\StoreBannerAdRequest;
use App\Models\BannerAd;
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

class CreateBannerAd extends Component
{
    use WithFileUploads;

    public string $level = 'general';

    public string $headline = '';

    public string $ctaText = '';

    public float $basePricePerImpression = 0.20;

    public float $budget = 0;

    public ?int $countyId = null;

    public ?int $subCountyId = null;

    public ?int $wardId = null;

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
            'banner' => $this->level = 'general',
            'audio' => $this->level = 'county',
            'interstitial' => $this->level = 'subcounty',
            'rewarded' => $this->level = 'ward',
            default => $this->level = 'general',
        };

        if ($price !== null) {
            $this->basePricePerImpression = (float) $price;
        }
    }

    public function save()
    {
        $request = new StoreBannerAdRequest;
        $validated = $this->validate($request->rules());

        $pricingService = new BannerPricingService;
        $county = $this->countyId ? County::find($this->countyId) : null;

        $pricingResult = $pricingService->calculate(
            level: $this->level,
            basePricePerImpression: $this->basePricePerImpression,
            budget: $this->budget,
            county: $county,
        );

        // Handle file upload
        $imagePath = null;
        if ($this->bannerImage) {
            $filename = 'banner_'.uniqid().'.'.$this->bannerImage->getClientOriginalExtension();
            $this->bannerImage->storeAs('banner-uploads', $filename, 'public');
            $imagePath = '/storage/banner-uploads/'.$filename;
        }

        // Get target UID based on level
        $targetUid = null;
        if ($this->level === 'county' && $this->countyId) {
            $county = County::find($this->countyId);
            $targetUid = $county->uid ?? null;
        } elseif ($this->level === 'subcounty' && $this->subCountyId) {
            $subCounty = SubCounty::find($this->subCountyId);
            $county = $subCounty->county;
            $targetUid = $subCounty->uid ?? null;
        } elseif ($this->level === 'ward' && $this->wardId) {
            $ward = Ward::find($this->wardId);
            $targetUid = $ward->uid ?? null;
        }

        $bannerAd = BannerAd::create([
            'uuid' => Str::uuid(),
            'user_id' => Auth::id(),
            'headline' => $this->headline,
            'cta_text' => $this->ctaText,
            'target_level' => $this->level,
            'target_county_id' => $this->countyId,
            'target_sub_county_id' => $this->subCountyId,
            'target_ward_id' => $this->wardId,
            'base_price_per_impression' => $this->basePricePerImpression,
            'price_per_impression' => $pricingResult->finalPricePerImpression,
            'budget' => $this->budget,
            'max_impressions' => $pricingResult->maxImpressions,
            'status' => BannerAdStatus::Pending->value,
            'is_active' => false,
            'priority' => 1,
            'starts_at' => now(),
            'ends_at' => now()->addDays(30),
        ]);

        if ($imagePath) {
            $bannerAd->image_url = $imagePath;
            $bannerAd->save();
        }

        $this->reset();

        session()->flash('success', "Banner ad saved! Final price: KES {$pricingResult->finalPricePerImpression} | Max impressions: {$pricingResult->maxImpressions}");

        $this->dispatch('closeBannerModal');
    }

    public function render(): View
    {
        return view('livewire.ads.create-banner-ad');
    }
}
