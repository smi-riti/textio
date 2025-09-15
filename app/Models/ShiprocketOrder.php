<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiprocketOrder extends Model
{
    protected $fillable = [
        'order_id','shiprocket_order_id','shipment_id','awb_code',
        'courier_company_id','estimated_delivery_date',
        'shipped_at','delivered_at','delivery_notes','status','raw_payload'
    ];

    protected $casts = [
        'raw_payload' => 'array',
        'estimated_delivery_date' => 'date',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
