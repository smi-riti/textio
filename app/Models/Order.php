<?php

namespace App\Models;

use App\Services\PaymentService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
   protected $fillable = [
        'user_id','address_id','order_number','isOrdered','status',
        'total_amount','shipping_charge','coupon_code',
        'return_status','return_reason','return_requested_at',
        'cancelled_at','cancellation_reason',
    ];

      protected $casts = [
        'cancelled_at' => 'datetime',
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

    // app/Models/Order.php
public function shiprocketOrder()
{
    return $this->hasOne(ShiprocketOrder::class);
}

public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}

    // Order status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELED = 'canceled';

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled(): bool
    {
        $cancellableStatuses = [self::STATUS_PENDING, self::STATUS_PROCESSING];
        return in_array($this->status, $cancellableStatuses);
    }

    /**
     * Cancel the order and handle refund if necessary
     */
    public function cancel(string $reason): void
    {
        if (!$this->canBeCancelled()) {
            throw new \Exception('This order cannot be cancelled.');
        }

        app(PaymentService::class)->handleOrderCancellation($this, $reason);
    }
}
