<?php

return [
    'price_tiers' => [0.20, 0.30, 0.40],

    'geo_multipliers' => [
        'general' => 1.0,
        'county' => 1.3,
        'subcounty' => 1.5,
        'ward' => 1.8,
    ],

    // Keyed by county_uid (from kenya_wards / counties table)
    'urban_multipliers' => [
        '047' => ['county' => 1.6, 'subcounty' => 1.95, 'ward' => 2.3], // Nairobi
        '001' => ['county' => 1.45, 'subcounty' => 1.9, 'ward' => 2.2], // Mombasa
        '022' => ['county' => 1.4, 'subcounty' => 1.85, 'ward' => 2.15], // Kiambu
        '011' => ['county' => 1.35, 'subcounty' => 1.8, 'ward' => 2.1], // Kajiado
        '032' => ['county' => 1.3, 'subcounty' => 1.75, 'ward' => 2.05], // Nakuru
        '042' => ['county' => 1.25, 'subcounty' => 1.7, 'ward' => 2.0], // Kisumu
        '016' => ['county' => 1.2, 'subcounty' => 1.65, 'ward' => 1.95], // Machakos
        '027' => ['county' => 1.15, 'subcounty' => 1.6, 'ward' => 1.9], // Uasin Gishu
    ],

    'default_urban_multiplier' => 1.0,
];
