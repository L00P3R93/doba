<?php

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;

enum BannerAdStatus: string implements HasColor, HasIcon, HasLabel
{
    case Pending = 'pending';
    case Active = 'active';
    case Paused = 'paused';
    case Completed = 'completed';
    case Rejected = 'rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Active => 'Active',
            self::Paused => 'Paused',
            self::Completed => 'Completed',
            self::Rejected => 'Rejected',
        };
    }

    public function getIcon(): BackedEnum
    {
        return match ($this) {
            self::Pending => Heroicon::OutlinedClock,
            self::Active => Heroicon::OutlinedCheckCircle,
            self::Paused => Heroicon::OutlinedPauseCircle,
            self::Completed => Heroicon::OutlinedCheckBadge,
            self::Rejected => Heroicon::OutlinedXCircle,
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Active => 'success',
            self::Paused => 'gray',
            self::Completed => 'info',
            self::Rejected => 'danger',
        };
    }
}
