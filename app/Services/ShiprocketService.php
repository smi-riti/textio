<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\ShiprocketOrder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ShiprocketService
{
    /**
     * Create a Shiprocket order/shipment based on the local Order model.
     * Calculates total weight/dimensions and prepares payload.
     * Updates shiprocket_orders table and syncs to Shiprocket.
     *
     * @param Order $order
     * @return ShiprocketOrder
     * @throws \Exception
     */
    public function createShipment(Order $order)
    {
        $address = $order->address;
        $user = $order->user;
        $payment = $order->payment;

        // Prepare order items for Shiprocket
        $orderItems = [];
        $totalWeight = 0;
        $maxLength = 0;
        $maxBreadth = 0;
        $maxHeight = 0;

        foreach ($order->orderItems as $item) {
            $product = $item->product;
            $quantity = $item->quantity;

            $orderItems[] = [
                'name' => $product->name,
                'sku' => $product->slug ?? 'SKU-' . $product->id,
                'units' => $quantity,
                'selling_price' => $item->unit_price,
                'discount' => 0,
                'tax' => 0,
                'hsn' => '',
            ];

            $totalWeight += (($product->weight ?? 500) * $quantity) / 1000;
            $maxLength = max($maxLength, $product->length ?? 10);
            $maxBreadth = max($maxBreadth, $product->breadth ?? 10);
            $maxHeight = max($maxHeight, $product->height ?? 5);
        }

        $payload = [
            'order_id' => $order->order_number,
            'order_date' => $order->created_at->format('Y-m-d H:i'),
            'pickup_location' => 'Home', // From previous fix
            'channel_id' => env('SHIPROCKET_CHANNEL_ID', '4421134'), // Default channel ID
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
            'payment_method' => ($payment->payment_method === Payment::METHOD_COD) ? 'COD' : 'Prepaid',
            'sub_total' => $order->total_amount,
            'length' => $maxLength,
            'breadth' => $maxBreadth,
            'height' => $maxHeight,
            'weight' => $totalWeight,
        ];

        try {
            // Fetch token
            $tokenResponse = Http::post('https://apiv2.shiprocket.in/v1/external/auth/login', [
                'email' => env('SHIPROCKET_EMAIL'),
                'password' => env('SHIPROCKET_PASSWORD'),
            ]);

            if (!$tokenResponse->successful()) {
                Log::error('Shiprocket token fetch failed: ' . $tokenResponse->body());
                throw new \Exception('Failed to authenticate with Shiprocket.');
            }

            $token = $tokenResponse->json()['token'] ?? null;
            if (empty($token)) {
                throw new \Exception('Failed to obtain Shiprocket token.');
            }

            // Create shipment
            $response = Http::withToken($token)
                ->post('https://apiv2.shiprocket.in/v1/external/orders/create', $payload);

            if ($response->successful()) {
                $data = $response->json();

                if (!isset($data['order_id']) || !isset($data['shipment_id']) || !isset($data['awb_code'])) {
                    Log::error('Shiprocket create failed: Invalid response data', ['response' => $data]);
                    throw new \Exception('Failed to create Shiprocket shipment: Invalid response.');
                }

                // Store in shiprocket_orders (updates DB)
                $shiprocket = ShiprocketOrder::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'shiprocket_order_id' => $data['order_id'],
                        'shipment_id' => $data['shipment_id'],
                        'awb_code' => $data['awb_code'],
                        'courier_company_id' => $data['courier_company_id'] ?? null,
                        'status' => 'confirmed',
                        'raw_payload' => json_encode($data),
                    ]
                );

                // Update order status to 'processing'
                $order->update(['status' => 'processing']);

                Log::info('Shiprocket shipment created for order ' . $order->order_number, ['awb_code' => $data['awb_code']]);

                return $shiprocket;
            } else {
                Log::error('Shiprocket create failed: ' . $response->body());
                throw new \Exception('Failed to create Shiprocket shipment: ' . ($response->json()['message'] ?? 'Unknown error.'));
            }
        } catch (\Exception $e) {
            Log::error('Shiprocket error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Track shipment by AWB code from Shiprocket API.
     * Updates shiprocket_orders table with latest status and syncs to orders.status.
     * Returns tracking data for admin display.
     *
     * @param string $awbCode
     * @return array Tracking data
     * @throws \Exception
     */
    public function trackShipment(string $awbCode)
    {
        try {
            // Fetch token
            $tokenResponse = Http::post('https://apiv2.shiprocket.in/v1/external/auth/login', [
                'email' => env('SHIPROCKET_EMAIL'),
                'password' => env('SHIPROCKET_PASSWORD'),
            ]);

            if (!$tokenResponse->successful()) {
                Log::error('Shiprocket token fetch failed for tracking: ' . $tokenResponse->body());
                throw new \Exception('Failed to authenticate with Shiprocket.');
            }

            $token = $tokenResponse->json()['token'] ?? null;
            if (empty($token)) {
                throw new \Exception('Failed to obtain Shiprocket token.');
            }

            // Track by AWB
            $response = Http::withToken($token)
                ->get("https://apiv2.shiprocket.in/v1/external/courier/track/awb/{$awbCode}");

            if ($response->successful()) {
                $data = $response->json();

                if (!isset($data['data']['shipment_status'])) {
                    Log::error('Shiprocket tracking failed: Invalid response data', ['response' => $data]);
                    throw new \Exception('Invalid tracking response from Shiprocket.');
                }

                // Find the ShiprocketOrder by AWB
                $shiprocketOrder = ShiprocketOrder::where('awb_code', $awbCode)->first();
                if (!$shiprocketOrder) {
                    throw new \Exception('Shipment not found in database.');
                }

                // Update shiprocket_orders table
                $shiprocketOrder->update([
                    'status' => $data['data']['shipment_status'], // e.g., 'in_transit', 'delivered'
                    'raw_payload' => json_encode($data),
                    'delivered_at' => $data['data']['shipment_status'] === 'delivered' ? now() : null,
                ]);

                // Sync to orders table
                $order = $shiprocketOrder->order;
                $order->update(['status' => $data['data']['shipment_status']]);

                Log::info('Shiprocket tracking updated for AWB ' . $awbCode, ['status' => $data['data']['shipment_status']]);

                return $data['data']; // Return tracking details for admin display
            } else {
                Log::error('Shiprocket tracking failed: ' . $response->body());
                throw new \Exception('Failed to track shipment: ' . ($response->json()['message'] ?? 'Unknown error.'));
            }
        } catch (\Exception $e) {
            Log::error('Shiprocket tracking error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cancel shipment in Shiprocket (optional, for admin-initiated cancellations).
     * Updates shiprocket_orders and orders tables.
     *
     * @param string $shipmentId
     * @return bool Success
     * @throws \Exception
     */
    public function cancelShipment(string $shipmentId)
    {
        try {
            $tokenResponse = Http::post('https://apiv2.shiprocket.in/v1/external/auth/login', [
                'email' => env('SHIPROCKET_EMAIL'),
                'password' => env('SHIPROCKET_PASSWORD'),
            ]);

            $token = $tokenResponse->json()['token'] ?? null;
            if (empty($token)) {
                throw new \Exception('Failed to obtain Shiprocket token.');
            }

            $response = Http::withToken($token)
                ->post('https://apiv2.shiprocket.in/v1/external/courier/cancel/shipment', [
                    'shipment_id' => $shipmentId,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                $shiprocketOrder = ShiprocketOrder::where('shipment_id', $shipmentId)->first();
                if ($shiprocketOrder) {
                    $shiprocketOrder->update(['status' => 'cancelled']);
                    $shiprocketOrder->order->update(['status' => 'canceled']);
                }

                Log::info('Shiprocket shipment cancelled for ID ' . $shipmentId);

                return true;
            } else {
                Log::error('Shiprocket cancel failed: ' . $response->body());
                throw new \Exception('Failed to cancel shipment.');
            }
        } catch (\Exception $e) {
            Log::error('Shiprocket cancel error: ' . $e->getMessage());
            throw $e;
        }
    }
}