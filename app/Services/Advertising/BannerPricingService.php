<?php

namespace App\Services\Advertising;

use App\Models\County;

final readonly class BannerPricingService
{
    public function calculate(
        string $level,
        float $basePricePerImpression,
        float $budget,
        ?County $county = null,
    ): BannerPricingResult {
        $geoMultiplier = config('advertising.geo_multipliers.'.$level, 1.0);

        $urbanMultiplier = config('advertising.default_urban_multiplier', 1.0);

        if ($county && $level !== 'general') {
            $urbanMultipliers = config('advertising.urban_multipliers', []);
            $urbanMultiplier = $urbanMultipliers[$county->uid][$level] ?? $urbanMultiplier;
        }

        $finalPrice = round($basePricePerImpression * $geoMultiplier * $urbanMultiplier, 2);

        $maxImpressions = $finalPrice > 0 ? (int) floor($budget / $finalPrice) : 0;

        return new BannerPricingResult(
            geoMultiplier: $geoMultiplier,
            urbanMultiplier: $urbanMultiplier,
            finalPricePerImpression: $finalPrice,
            maxImpressions: $maxImpressions,
        );
    }
}
