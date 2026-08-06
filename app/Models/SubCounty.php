<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubCounty extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'name',
        'county_id',
    ];

    public function county(): BelongsTo
    {
        return $this->belongsTo(County::class);
    }

    public function wards(): HasMany
    {
        return $this->hasMany(Ward::class);
    }
}
