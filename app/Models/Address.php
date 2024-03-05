<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    use HasFactory;

    public const STORE_ADDRESS = 'store_address';
    public const DELIVERY_ADDRESS = 'delivery_address';
    public const SHIPPING_ADDRESS = 'shipping_address';

    protected $attributes = [
        'type' => self::STORE_ADDRESS
    ];

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }
}
