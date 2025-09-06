<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $casts = [
        'expiration_date' => 'datetime',
        'status' => 'boolean',
                'start_date' => 'datetime',

    ];

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'start_date',
        'expiration_date',
        'minimum_purchase_amount',
        'status',
    ];
}