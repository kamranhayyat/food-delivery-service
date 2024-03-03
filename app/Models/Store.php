<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Store extends Model
{
    use HasFactory;

    public const PENDING_STORE = 0;
    public const APPROVED_STORE = 1;
    public const DISAPPROVED_STORE = 2;

    protected $attributes = [
        'status' => self::PENDING_STORE
    ];

    public const STORE_ADDRESS_TYPE = 'store_address';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }
}
