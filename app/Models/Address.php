<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
      protected $fillable = [
        'user_id', // Added to allow mass assignment
        'name',
        'phone',
        'alt_phone',
        'address_type',
        'landmark',
        'street',
        'area',
        'address_line',
        'city',
        'state',
        'postal_code',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
