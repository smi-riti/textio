<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Razorpay\Api\Utility;

class PaymentService
{
    private $api;
    private $keyId;
    private $keySecret;

    public function __construct()
    {
        $this->keyId = config('services.razorpay.key', env('RAZORPAY_KEY'));
        $this->keySecret = config('services.razorpay.secret', env('RAZORPAY_SECRET'));

        if (empty($this->keyId) || empty($this->keySecret)) {
            Log::error('Razorpay credentials not configured', [
                'key_present' => !empty($this->keyId),
                'secret_present' => !empty($this->keySecret)
            ]);
            throw new \Exception('Payment gateway not configured. Please contact support.');
        }

        // Validate key format
        if (!preg_match('/^rzp_(test_|live_)[a-zA-Z0-9]{14}$/', $this->keyId)) {
            Log::error('Invalid Razorpay key format', ['key' => $this->keyId]);
            throw new \Exception('Invalid payment gateway configuration.');
        }

        try {
            $this->api = new Api($this->keyId, $this->keySecret);
        } catch (\Exception $e) {
            Log::error('Failed to initialize Razorpay API', [
                'error' => $e->getMessage()
            ]);
            throw new \Exception('Payment gateway initialization failed.');
        }
    }

    /**
     * Create a Razorpay order for the given order
     */
    public function createRazorpayOrder(Order $order, float $amount): array
    {
        try {
            Log::info('Creating Razorpay order', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'amount' => $amount
            ]);

            $razorpayOrder = $this->api->order->create([
                'receipt' => $order->order_number,
                'amount' => intval($amount * 100), // Convert to paise
                'currency' => 'INR',
                'notes' => [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id
                ]
            ]);

            $orderData = $razorpayOrder->toArray();

            Log::info('Razorpay order created successfully', [
                'razorpay_order_id' => $orderData['id'],
                'order_number' => $order->order_number
            ]);

            return $orderData;

        } catch (\Exception $e) {
            Log::error('Failed to create Razorpay order', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \Exception('Failed to create payment order: ' . $e->getMessage());
        }
    }

    /**
     * Verify payment signature and update payment status
     */
      public function verifyPayment(array $paymentData): bool
    {
        try {
            Log::info('Verifying payment signature', ['payment_data' => $paymentData]);

            if (!isset($paymentData['razorpay_order_id']) || 
                !isset($paymentData['razorpay_payment_id']) || 
                !isset($paymentData['razorpay_signature'])) {
                throw new \Exception('Missing payment verification data');
            }

            $attributes = [
                'razorpay_order_id' => $paymentData['razorpay_order_id'],
                'razorpay_payment_id' => $paymentData['razorpay_payment_id'],
                'razorpay_signature' => $paymentData['razorpay_signature']
            ];

            // Create Utility instance and call verifyPaymentSignature
            $utility = new Utility();
            $utility->verifyPaymentSignature($attributes);

            Log::info('Payment signature verified successfully', [
                'razorpay_order_id' => $paymentData['razorpay_order_id'],
                'razorpay_payment_id' => $paymentData['razorpay_payment_id']
            ]);

            return true;

        } catch (SignatureVerificationError $e) {
            Log::error('Payment signature verification failed', [
                'payment_data' => $paymentData,
                'error' => $e->getMessage()
            ]);
            return false;
            
        } catch (\Exception $e) {
            Log::error('Payment verification error', [
                'payment_data' => $paymentData,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Update payment status after successful verification
     */
    public function updatePaymentStatus(string $razorpayOrderId, array $paymentData): Payment
    {
        return DB::transaction(function () use ($razorpayOrderId, $paymentData) {
            $payment = Payment::where('razorpay_order_id', $razorpayOrderId)
                ->lockForUpdate()
                ->firstOrFail();

            // Prevent duplicate processing
            if ($payment->payment_status === 'paid') {
                Log::warning('Payment already processed', [
                    'payment_id' => $payment->id,
                    'razorpay_order_id' => $razorpayOrderId
                ]);
                return $payment;
            }

            $payment->update([
                'payment_status' => 'paid',
                'razorpay_payment_id' => $paymentData['razorpay_payment_id'],
                'razorpay_signature' => $paymentData['razorpay_signature'],
                'payment_date' => now(),
                'notes' => array_merge($payment->notes ?? [], [
                    'verified_at' => now()->toISOString(),
                    'verification_method' => 'signature'
                ])
            ]);

            Log::info('Payment status updated successfully', [
                'payment_id' => $payment->id,
                'order_id' => $payment->order_id,
                'status' => 'paid'
            ]);

            return $payment;
        });
    }

    /**
     * Handle payment failure
     */
    public function handlePaymentFailure(string $razorpayOrderId, string $reason = ''): void
    {
        try {
            $payment = Payment::where('razorpay_order_id', $razorpayOrderId)->first();

            if ($payment) {
                $payment->update([
                    'payment_status' => 'failed',
                    'notes' => array_merge($payment->notes ?? [], [
                        'failed_at' => now()->toISOString(),
                        'failure_reason' => $reason
                    ])
                ]);

                Log::warning('Payment marked as failed', [
                    'payment_id' => $payment->id,
                    'order_id' => $payment->order_id,
                    'reason' => $reason
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to update payment failure status', [
                'razorpay_order_id' => $razorpayOrderId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get payment status from Razorpay
     */
    public function getPaymentStatus(string $paymentId): array
    {
        try {
            $payment = $this->api->payment->fetch($paymentId);
            return $payment->toArray();
        } catch (\Exception $e) {
            Log::error('Failed to fetch payment status from Razorpay', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Refund a payment
     */
    public function refundPayment(string $paymentId, float $amount = null): array
    {
        try {
            $refundData = ['payment_id' => $paymentId];

            if ($amount !== null) {
                $refundData['amount'] = intval($amount * 100); // Convert to paise
            }

            $refund = $this->api->refund->create($refundData);

            Log::info('Refund created successfully', [
                'payment_id' => $paymentId,
                'refund_id' => $refund->id,
                'amount' => $amount
            ]);

            return $refund->toArray();
        } catch (\Exception $e) {
            Log::error('Failed to create refund', [
                'payment_id' => $paymentId,
                'amount' => $amount,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}