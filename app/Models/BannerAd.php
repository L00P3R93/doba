<?php

namespace App\Models;

use App\Enums\BannerAdStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BannerAd extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'headline',
        'cta_text',
        'target_level',
        'target_county_id',
        'target_sub_county_id',
        'target_ward_id',
        'image_url',
        'base_price_per_impression',
        'price_per_impression',
        'budget',
        'max_impressions',
        'impressions',
        'clicks',
        'status',
        'is_active',
        'priority',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => BannerAdStatus::class,
            'is_active' => 'bool',
            'base_price_per_impression' => 'decimal:2',
            'price_per_impression' => 'decimal:2',
            'budget' => 'decimal:2',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function targetCounty(): BelongsTo
    {
        return $this->belongsTo(County::class, 'target_county_id');
    }

    public function targetSubCounty(): BelongsTo
    {
        return $this->belongsTo(SubCounty::class, 'target_sub_county_id');
    }

    public function targetWard(): BelongsTo
    {
        return $this->belongsTo(Ward::class, 'target_ward_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForLevel($query, string $level)
    {
        return $query->where('target_level', $level);
    }

    public function scopeEligibleForServing($query)
    {
        return $query->where('status', BannerAdStatus::Active)
            ->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            })
            ->whereColumn('impressions', '<', 'max_impressions');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->attributes['image_url'] ?? null;
    }
}
