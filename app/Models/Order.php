<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
   protected $fillable = [
        'user_id','address_id','order_number','isOrdered','status',
        'total_amount','shipping_charge','coupon_code',
        'return_status','return_reason','return_requested_at',
        'cancelled_at','cancellation_reason'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function payment() {

        return $this->hasOne(Payment::class);
    }
    public function shiprocket() {
        
        return $this->hasOne(ShiprocketOrder::class);
    }

    
}
