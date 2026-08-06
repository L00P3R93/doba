<?php

namespace App\Services\Advertising;

readonly class BannerPricingResult
{
    public function __construct(
        public float $geoMultiplier,
        public float $urbanMultiplier,
        public float $finalPricePerImpression,
        public int $maxImpressions,
    ) {}
}
