<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class County extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'name',
    ];

    public function subCounties(): HasMany
    {
        return $this->hasMany(SubCounty::class);
    }

    public function wards(): HasMany
    {
        return $this->hasMany(Ward::class);
    }
}
