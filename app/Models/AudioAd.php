<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AudioAd extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'advertiser',
        'headline',
        'cta_text',
        'cta_url',
        'audio_url',
        'banner_image',
        'is_active',
        'priority',
        'daily_limit',
        'impressions',
        'target_level',
        'target_uid',
        'clicks',
        'completions',
        'skips',
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