<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ShiprocketOrder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShiprocketService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.shiprocket.base_url', 'https://apiv2.shiprocket.in');
    }

    protected function getToken(): ?string
    {
        if (Cache::has('shiprocket_lockout')) {
            $lockoutUntil = Cache::get('shiprocket_lockout');
            if (now()->lessThan($lockoutUntil)) {
                Log::warning('Shiprocket API locked out until: ' . $lockoutUntil);
                return null;
            }
        }

        return Cache::remember('shiprocket_token', config('services.shiprocket.token_cache_ttl', 14400), function () {
            $email = config('services.shiprocket.email');
            $password = config('services.shiprocket.password');

            Log::info('Attempting Shiprocket authentication', [
                'email' => $email,
                'base_url' => $this->baseUrl,
            ]);

            try {
                $response = Http::timeout(10)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post("{$this->baseUrl}/v1/external/auth/login", [
                        'email' => $email,
                        'password' => $password,
                    ]);

                if (!$response->successful()) {
                    $body = $response->body();
                    if (str_contains($body, '<!DOCTYPE html>')) {
                        Log::error('Shiprocket server error (HTML response): ' . substr($body, 0, 500));
                        throw new \Exception('Shiprocket API server error, possibly under maintenance.');
                    }
                    if ($response->status() === 400 && str_contains($body, 'Too many failed login attempts')) {
                        Cache::put('shiprocket_lockout', now()->addMinutes(30), now()->addMinutes(30));
                        Log::error('Shiprocket token fetch failed: Too many login attempts, locked out for 30 minutes', ['response' => $body]);
                        throw new \Exception('Shiprocket API locked out for 30 minutes.');
                    }
                    Log::error('Shiprocket token fetch failed', [
                        'status' => $response->status(),
                        'response' => $body,
                        'email_used' => $email,
                    ]);
                    throw new \Exception('Failed to authenticate with Shiprocket: ' . ($response->json()['message'] ?? 'Unknown error'));
                }

                $token = $response->json()['token'] ?? null;
                if (empty($token)) {
                    Log::error('Shiprocket token fetch failed: No token in response', ['response' => $response->json()]);
                    throw new \Exception('Failed to obtain Shiprocket token.');
                }

                Log::info('Shiprocket token obtained successfully', ['token' => substr($token, 0, 10) . '...']);
                return $token;
            } catch (\Exception $e) {
                Log::error('Shiprocket authentication error: ' . $e->getMessage());
                throw $e;
            }
        });
    }

    protected function getValidPickupLocation(string $token): ?string
    {
        try {
            $response = Http::withToken($token)
                ->get("{$this->baseUrl}/v1/external/settings/company/pickup");

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['data']['shipping_address']) && is_array($data['data']['shipping_address']) && !empty($data['data']['shipping_address'][0]['pickup_location'])) {
                    $pickup = $data['data']['shipping_address'][0]['pickup_location'];
                    Log::info('Fetched valid pickup location', ['pickup_location' => $pickup]);
                    return $pickup;
                }
                Log::error('No valid pickup locations found', ['response' => $data]);
                throw new \Exception('No valid pickup locations available.');
            }

            Log::error('Failed to fetch pickup locations', ['status' => $response->status(), 'response' => $response->body()]);
            throw new \Exception('Failed to fetch pickup locations: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Pickup location fetch error: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function getValidChannelId(string $token): ?string
    {
        try {
            $response = Http::withToken($token)
                ->get("{$this->baseUrl}/v1/external/channels");

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['data']) && is_array($data['data']) && !empty($data['data'][0]['id'])) {
                    $channelId = $data['data'][0]['id'];
                    Log::info('Fetched valid channel ID', ['channel_id' => $channelId]);
                    return $channelId;
                }
                Log::error('No channels found', ['response' => $data]);
                throw new \Exception('No valid channels available.');
            }

            Log::error('Failed to fetch channels', ['status' => $response->status(), 'response' => $response->body()]);
            throw new \Exception('Failed to fetch channels: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Channel ID fetch error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createShipment(Order $order): ?ShiprocketOrder
    {
        $token = $this->getToken();
        if (!$token) {
            Log::warning('Skipping shipment creation due to API lockout', ['order_id' => $order->id]);
            return null;
        }

        try {
            $pickupLocation = $this->getValidPickupLocation($token);
            $channelId = $this->getValidChannelId($token);
        } catch (\Exception $e) {
            Log::error('Failed to fetch pickup location or channel ID', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            throw new \Exception('Cannot create shipment: ' . $e->getMessage());
        }

        $address = $order->address;
        $user = $order->user;
        $payment = $order->payment;

        if (!$address || !$user || !$payment) {
            Log::error('Invalid order data', ['order_id' => $order->id, 'address' => $address, 'user' => $user, 'payment' => $payment]);
            throw new \Exception('Invalid order data: Missing address, user, or payment information.');
        }

        $orderItems = [];
        $totalWeight = 0;
        $maxLength = 0;
        $maxBreadth = 0;
        $maxHeight = 0;

        foreach ($order->orderItems as $item) {
            $product = $item->product;
            $quantity = $item->quantity;

            $sku = $product->sku ?? $product->slug ?? 'SKU-' . $product->id;
            $sku = substr($sku, 0, 50); // Ensure SKU is 50 characters or less

            $orderItems[] = [
                'name' => $product->name,
                'sku' => $sku,
                'units' => $quantity,
                'selling_price' => $item->unit_price,
                'discount' => 0,
                'tax' => 0,
                'hsn' => $product->hsn_code ?? '',
            ];

            $totalWeight += (($product->weight ?? 500) * $quantity) / 1000; // grams to kg
            $maxLength = max($maxLength, $product->length ?? 10);
            $maxBreadth = max($maxBreadth, $product->breadth ?? 10);
            $maxHeight = max($maxHeight, $product->height ?? 5);
        }

        $payload = [
            'order_id' => $order->order_number,
            'order_date' => $order->created_at->format('Y-m-d H:i'),
            'pickup_location' => $pickupLocation,
            'channel_id' => $channelId,
            'billing_customer_name' => $address->name,
            'billing_last_name' => '',
            'billing_address' => $address->address_line,
            'billing_address_2' => '',
            'billing_city' => $address->city,
            'billing_pincode' => $address->postal_code,
            'billing_state' => $address->state,
            'billing_country' => 'India',
            'billing_email' => $user->email,
            'billing_phone' => $address->phone,
            'shipping_is_billing' => true,
            'order_items' => $orderItems,
            'payment_method' => ($payment->payment_method === 'cod') ? 'COD' : 'Prepaid',
            'sub_total' => $order->total_amount,
            'length' => $maxLength,
            'breadth' => $maxBreadth,
            'height' => $maxHeight,
            'weight' => $totalWeight,
        ];

        try {
            Log::info('Creating Shiprocket shipment', ['order_id' => $order->id, 'payload' => $payload]);
            $response = Http::withToken($token)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("{$this->baseUrl}/v1/external/orders/create/adhoc", $payload);

            if ($response->successful()) {
                $data = $response->json();
                if (!isset($data['order_id']) || !isset($data['shipment_id']) || !isset($data['awb_code'])) {
                    Log::error('Shiprocket create failed: Invalid response', ['response' => $data]);
                    throw new \Exception('Invalid response from Shiprocket.');
                }

                $shiprocket = ShiprocketOrder::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'shiprocket_order_id' => $data['order_id'],
                        'shipment_id' => $data['shipment_id'],
                        'awb_code' => $data['awb_code'],
                        'courier_company_id' => $data['courier_company_id'] ?? null,
                        'status' => 'confirmed',
                        'raw_payload' => $data,
                    ]
                );

                $order->update(['status' => 'processing']);

                Log::info('Shiprocket shipment created', ['order' => $order->order_number, 'awb' => $data['awb_code']]);
                return $shiprocket;
            }

            Log::error('Shiprocket create failed', ['status' => $response->status(), 'response' => $response->body()]);
            throw new \Exception('Failed to create shipment: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Shiprocket error: ' . $e->getMessage(), ['order_id' => $order->id]);
            throw new \Exception('Shipment creation failed: ' . $e->getMessage(), 0, $e);
        }
    }

    public function trackShipment(string $awbCode): array
    {
        $token = $this->getToken();
        if (!$token) {
            throw new \Exception('Cannot track shipment due to API lockout.');
        }

        try {
            Log::info('Tracking Shiprocket shipment', ['awb' => $awbCode]);
            $response = Http::withToken($token)
                ->get("{$this->baseUrl}/v1/external/courier/track/awb/{$awbCode}");

            if ($response->successful()) {
                $data = $response->json();

                if (!isset($data['tracking_data']['shipment_status'])) {
                    Log::error('Shiprocket tracking failed: Invalid response', ['response' => $data]);
                    throw new \Exception('Invalid tracking response.');
                }

                $shiprocketOrder = ShiprocketOrder::where('awb_code', $awbCode)->firstOrFail();
                $status = strtolower($data['tracking_data']['shipment_status']);

                $shiprocketOrder->update([
                    'status' => in_array($status, ['pending', 'confirmed', 'shipped', 'in_transit', 'out_for_delivery', 'delivered', 'cancelled', 'returned'])
                        ? $status
                        : 'pending',
                    'raw_payload' => $data,
                    'delivered_at' => $status === 'delivered' ? now() : null,
                ]);

                $shiprocketOrder->order->update(['status' => $status]);

                Log::info('Shiprocket tracking updated', ['awb' => $awbCode, 'status' => $status]);
                return $data['tracking_data'];
            }

            Log::error('Shiprocket tracking failed', ['status' => $response->status(), 'response' => $response->body()]);
            throw new \Exception('Failed to track: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Tracking error: ' . $e->getMessage(), ['awb' => $awbCode]);
            throw $e;
        }
    }

    public function cancelShipment(string $shipment_id): bool
    {
        $token = $this->getToken();
        if (!$token) {
            Log::warning('Skipping shipment cancellation due to API lockout', ['shipment_id' => $shipment_id]);
            throw new \Exception('Cannot cancel shipment due to API lockout.');
        }

        try {
            // Find the ShiprocketOrder to get the shiprocket_order_id
            $shiprocketOrder = ShiprocketOrder::where('shipment_id', $shipment_id)->firstOrFail();
            Log::info('Canceling Shiprocket order', ['shiprocket_order_id' => $shiprocketOrder->shiprocket_order_id]);

            $response = Http::withToken($token)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("{$this->baseUrl}/v1/external/orders/cancel", [
                    'ids' => [$shiprocketOrder->shiprocket_order_id],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Shiprocket order canceled successfully', ['shiprocket_order_id' => $shiprocketOrder->shiprocket_order_id, 'response' => $data]);

                // Update the ShiprocketOrder status to 'cancelled'
                $shiprocketOrder->update([
                    'status' => 'cancelled',
                    'raw_payload' => $data,
                ]);

                // Update the associated order status
                $shiprocketOrder->order->update(['status' => 'canceled']);

                return true;
            }

            Log::error('Shiprocket order cancellation failed', [
                'shiprocket_order_id' => $shiprocketOrder->shiprocket_order_id,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            throw new \Exception('Failed to cancel order: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Shiprocket cancellation error: ' . $e->getMessage(), ['shipment_id' => $shipment_id]);
            throw $e;
        }
    }
}