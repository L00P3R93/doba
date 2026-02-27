<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    /** @use HasFactory<\Database\Factories\ProfileFactory> */
    use Auditable, HasFactory, SoftDeletes;

    protected $table = 'profiles';

    protected $fillable = [
        'user_id',
        'bio',
        'mpesa_phone',
        'tier',
    ];

    protected function casts(): array
    {
        return [
            'tier' => 'string',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
