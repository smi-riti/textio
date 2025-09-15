<?php

return [
    'razorpay' => [
        'key' => env('RAZORPAY_KEY'),
        'secret' => env('RAZORPAY_SECRET'),
        'currency' => env('RAZORPAY_CURRENCY', 'INR'),
        'timeout' => env('RAZORPAY_TIMEOUT', 10), // seconds
    ],
    
    'payment_methods' => [
        'cod' => [
            'enabled' => env('PAYMENT_COD_ENABLED', true),
            'min_amount' => env('PAYMENT_COD_MIN_AMOUNT', 0),
            'max_amount' => env('PAYMENT_COD_MAX_AMOUNT', 50000),
        ],
        'upi' => [
            'enabled' => env('PAYMENT_UPI_ENABLED', true),
            'min_amount' => env('PAYMENT_UPI_MIN_AMOUNT', 1),
            'max_amount' => env('PAYMENT_UPI_MAX_AMOUNT', 100000),
        ],
    ],
    
    'order' => [
        'timeout' => env('PAYMENT_ORDER_TIMEOUT', 3600), // 1 hour in seconds
        'retry_attempts' => env('PAYMENT_RETRY_ATTEMPTS', 3),
        'auto_cancel_minutes' => env('PAYMENT_AUTO_CANCEL_MINUTES', 30),
    ],
];