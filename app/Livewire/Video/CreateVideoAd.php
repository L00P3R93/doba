<?php

namespace App\Livewire\Video;

use App\Enums\AdTargetLevel;
use App\Http\Requests\Advertising\StoreVideoAdRequest;
use App\Models\VideoAd;
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

class CreateVideoAd extends Component
{
    use WithFileUploads;

    public string $level = 'general';

    public string $advertiser = '';

    public string $headline = '';

    public string $ctaText = '';

    public string $videoUrl = '';

    public string $adType = 'interstitial';

    public float $basePricePerImpression = 0.20;

    public float $budget = 0;

    public ?int $countyId = null;

    public ?int $subCountyId = null;

    public ?int $wardId = null;

    public $videoFile = null;

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
            'interstitial' => $this->level = 'subcounty',
            'rewarded' => $this->level = 'ward',
            default => $this->level = 'general',
        };

        $this->adType = $adType;

        if ($price !== null) {
            $this->basePricePerImpression = (float) $price;
        }
    }

    public function save()
    {
        $request = new StoreVideoAdRequest;
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
        $videoPath = null;
        if ($this->videoFile) {
            $filename = 'video_'.uniqid().'.'.$this->videoFile->getClientOriginalExtension();
            $this->videoFile->storeAs('video-uploads', $filename, 'public');
            $videoPath = '/storage/video-uploads/'.$filename;
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

        $videoAd = VideoAd::create([
            'uuid' => Str::uuid(),
            'user_id' => Auth::id(),
            'advertiser' => $this->advertiser,
            'headline' => $this->headline,
            'cta_text' => $this->ctaText,
            'video_url' => $videoPath,
            'ad_type' => $this->adType,
            'is_active' => false,
            'priority' => 1,
            'daily_limit' => null,
            'max_impressions' => null,
            'impressions' => 0,
            'clicks' => 0,
            'completions' => 0,
            'skips' => 0,
            'price_per_impression' => $pricingResult->finalPricePerImpression,
            'target_level' => $this->level,
            'target_uid' => $targetUid,
        ]);

        $this->reset();

        session()->flash('success', "Video ad saved! Final price: KES {$pricingResult->finalPricePerImpression} | Max impressions: {$pricingResult->maxImpressions}");

        $this->dispatch('closeVideoModal');
    }

    public function render(): View
    {
        return view('livewire.video.create-video-ad');
    }
}