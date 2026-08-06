<?php

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum AdTargetLevel: string implements HasColor, HasIcon, HasLabel
{
    case General = 'general';
    case County = 'county';
    case SubCounty = 'subcounty';
    case Ward = 'ward';

    public function getLabel(): string
    {
        return match ($this) {
            self::General => 'General',
            self::County => 'County',
            self::SubCounty => 'Sub-County',
            self::Ward => 'Ward',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::General => 'Reach the whole platform',
            self::County => 'Target a specific county',
            self::SubCounty => 'Narrow to a sub-county',
            self::Ward => 'Hyper-local, ward-level targeting',
        };
    }

    public function getIcon(): BackedEnum
    {
        return match ($this) {
            self::General => Heroicon::OutlinedGlobeAlt,
            self::County => Heroicon::OutlinedMap,
            self::SubCounty, self::Ward => Heroicon::OutlinedMapPin,
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::General => 'primary',
            self::County => 'info',
            self::SubCounty => 'warning',
            self::Ward => 'success',
        };
    }

    public function baseGeoMultiplier(): float
    {
        return match ($this) {
            self::General => 1.0,
            self::County => 1.3,
            self::SubCounty => 1.5,
            self::Ward => 1.8,
        };
    }
}
