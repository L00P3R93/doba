<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideoAd extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'advertiser',
        'headline',
        'cta_text',
        'video_url',
        'ad_type',
        'is_active',
        'priority',
        'daily_limit',
        'max_impressions',
        'impressions',
        'clicks',
        'completions',
        'skips',
        'price_per_impression',
        'target_level',
        'target_uid',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'bool',
            'impressions' => 'integer',
            'clicks' => 'integer',
            'completions' => 'integer',
            'skips' => 'integer',
            'daily_limit' => 'integer',
            'max_impressions' => 'integer',
            'price_per_impression' => 'decimal:2',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}