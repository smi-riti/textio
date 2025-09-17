<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_method',
        'currency',
        'amount',
        'payment_status',
        'payment_date',
        'razorpay_payment_id',
        'razorpay_order_id',
        'razorpay_signature',
        'notes',
        'failure_reason',
        'retry_count'
    ];

    protected $casts = [
        'notes' => 'array',
        'payment_date' => 'datetime',
        'amount' => 'decimal:2',
        'retry_count' => 'integer',
    ];

    // Payment status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REFUNDED = 'refunded';

    // Payment method constants
    public const METHOD_COD = 'cod';
    public const METHOD_RAZORPAY = 'razorpay';

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Check if payment is successful
     */
    public function isPaid(): bool
    {
        return $this->payment_status === self::STATUS_PAID;
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->payment_status === self::STATUS_PENDING;
    }

    /**
     * Check if payment failed
     */
    public function isFailed(): bool
    {
        return $this->payment_status === self::STATUS_FAILED;
    }

    /**
     * Check if payment is COD
     */
    public function isCod(): bool
    {
        return $this->payment_method === self::METHOD_COD;
    }

    /**
     * Check if payment is online (Razorpay)
     */
    public function isOnline(): bool
    {
        return $this->payment_method === self::METHOD_RAZORPAY;
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', self::STATUS_PENDING);
    }

    /**
     * Scope for paid payments
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', self::STATUS_PAID);
    }

    /**
     * Scope for failed payments
     */
    public function scopeFailed($query)
    {
        return $query->where('payment_status', self::STATUS_FAILED);
    }
}
