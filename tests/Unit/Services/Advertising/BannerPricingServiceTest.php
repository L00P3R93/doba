<?php

use App\Models\County;
use App\Services\Advertising\BannerPricingService;

beforeEach(function () {
    config([
        'advertising.price_tiers' => [0.20, 0.30, 0.40],
        'advertising.geo_multipliers' => [
            'general' => 1.0,
            'county' => 1.3,
            'subcounty' => 1.5,
            'ward' => 1.8,
        ],
        'advertising.urban_multipliers' => [
            '047' => ['county' => 1.6, 'subcounty' => 1.95, 'ward' => 2.3],
            '001' => ['county' => 1.45, 'subcounty' => 1.9, 'ward' => 2.2],
            '022' => ['county' => 1.4, 'subcounty' => 1.85, 'ward' => 2.15],
            '011' => ['county' => 1.35, 'subcounty' => 1.8, 'ward' => 2.1],
            '032' => ['county' => 1.3, 'subcounty' => 1.75, 'ward' => 2.05],
            '042' => ['county' => 1.25, 'subcounty' => 1.7, 'ward' => 2.0],
            '016' => ['county' => 1.2, 'subcounty' => 1.65, 'ward' => 1.95],
            '027' => ['county' => 1.15, 'subcounty' => 1.6, 'ward' => 1.9],
        ],
        'advertising.default_urban_multiplier' => 1.0,
    ]);
});

it('calculates general level pricing correctly', function () {
    $service = new BannerPricingService;
    $result = $service->calculate(
        level: 'general',
        basePricePerImpression: 0.20,
        budget: 1000,
        county: null,
    );

    expect($result->geoMultiplier)->toBe(1.0);
    expect($result->urbanMultiplier)->toBe(1.0);
    expect($result->finalPricePerImpression)->toBe(0.20);
    expect($result->maxImpressions)->toBe(5000);
});

it('calculates urban county pricing correctly (Nairobi)', function () {
    $service = new BannerPricingService;
    $county = County::factory()->create(['uid' => '047']);

    $result = $service->calculate(
        level: 'county',
        basePricePerImpression: 0.20,
        budget: 1000,
        county: $county,
    );

    expect($result->geoMultiplier)->toBe(1.3);
    expect($result->urbanMultiplier)->toBe(1.6);
    expect($result->finalPricePerImpression)->toBe(0.42);
    expect($result->maxImpressions)->toBe(2380);
});

it('calculates rural county pricing correctly', function () {
    $service = new BannerPricingService;
    $county = County::factory()->create(['uid' => '999']);

    $result = $service->calculate(
        level: 'county',
        basePricePerImpression: 0.20,
        budget: 1000,
        county: $county,
    );

    expect($result->geoMultiplier)->toBe(1.3);
    expect($result->urbanMultiplier)->toBe(1.0);
    expect($result->finalPricePerImpression)->toBe(0.26);
    expect($result->maxImpressions)->toBe(3846);
});

it('calculates ward level pricing for urban county (Nairobi)', function () {
    $service = new BannerPricingService;
    $county = County::factory()->create(['uid' => '047']);

    $result = $service->calculate(
        level: 'ward',
        basePricePerImpression: 0.30,
        budget: 5000,
        county: $county,
    );

    expect($result->geoMultiplier)->toBe(1.8);
    expect($result->urbanMultiplier)->toBe(2.3);
    expect($result->finalPricePerImpression)->toBe(1.24);
    expect($result->maxImpressions)->toBe(4032);
});

it('handles zero or near-zero price correctly', function () {
    $service = new BannerPricingService;

    $result = $service->calculate(
        level: 'general',
        basePricePerImpression: 0.01,
        budget: 100,
        county: null,
    );

    expect($result->finalPricePerImpression)->toBe(0.01);
    expect($result->maxImpressions)->toBe(10000);
});

it('calculates all urban counties at county level', function () {
    $service = new BannerPricingService;
    $urbanCounties = [
        '047' => 1.6, // Nairobi
        '001' => 1.45, // Mombasa
        '022' => 1.4, // Kiambu
        '011' => 1.35, // Kajiado
        '032' => 1.3, // Nakuru
        '042' => 1.25, // Kisumu
        '016' => 1.2, // Machakos
        '027' => 1.15, // Uasin Gishu
    ];

    foreach ($urbanCounties as $uid => $expectedUrbanMultiplier) {
        $county = County::factory()->create(['uid' => $uid]);
        $result = $service->calculate(
            level: 'county',
            basePricePerImpression: 0.20,
            budget: 1000,
            county: $county,
        );

        expect($result->urbanMultiplier)->toBe($expectedUrbanMultiplier);
    }
});
