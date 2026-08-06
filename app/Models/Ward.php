<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ward extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'name',
        'sub_county_id',
        'county_id',
        'population',
    ];

    protected function casts(): array
    {
        return [
            'population' => 'int',
        ];
    }

    public function subCounty(): BelongsTo
    {
        return $this->belongsTo(SubCounty::class);
    }

    public function county(): BelongsTo
    {
        return $this->belongsTo(County::class);
    }
}
