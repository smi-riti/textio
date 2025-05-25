<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Cart extends Component
{
    public $order;

    public function mount()
    {
        $this->order = Order::where('user_id', Auth::id())->where('isOrdered', false)->first();
    }

    public function addToCart(Request $req, $product_slug)
    {
        $product = Product::where('slug', $product_slug)->first();
        if (!$product) {
            session()->flash('error', 'Product not found.');
            return redirect()->route('public.cart');
        }

        if ($product->stock <= 0) {
            session()->flash('error', 'Product is out of stock.');
            return redirect()->route('public.cart');
        }

        $size_variant_id = $req->input('size_variant_id');
        $color_variant_id = $req->input('color_variant_id');

        $size_variant = $size_variant_id ? ProductVariant::find($size_variant_id) : null;
        $color_variant = $color_variant_id ? ProductVariant::find($color_variant_id) : null;

        if (($size_variant_id && !$size_variant) || ($color_variant_id && !$color_variant)) {
            session()->flash('error', 'Invalid variant selected.');
            return redirect()->route('public.cart');
        }

        $exist_order = Order::where([
            ['user_id', Auth::id()],
            ['isOrdered', false]
        ])->first();

        if ($exist_order) {
            $exist_order_item = OrderItem::where([
                ['user_id', Auth::id()],
                ['order_id', $exist_order->id],
                ['product_id', $product->id],
                ['size_variant_id', $size_variant_id],
                ['color_variant_id', $color_variant_id]
            ])->first();

            if ($exist_order_item) {
                if ($exist_order_item->quantity + 1 > $product->stock) {
                    session()->flash('error', 'Cannot add more items than available stock.');
                    return redirect()->route('public.cart');
                }
                $exist_order_item->quantity += 1;
                $exist_order_item->save();
            } else {
                $order_item = new OrderItem();
                $order_item->user_id = Auth::id();
                $order_item->order_id = $exist_order->id;
                $order_item->product_id = $product->id;
                $order_item->size_variant_id = $size_variant_id;
                $order_item->color_variant_id = $color_variant_id;
                $order_item->quantity = 1;
                $order_item->save();
            }
        } else {
            $order = new Order();
            $order->user_id = Auth::id();
            $order->isOrdered = false;
            $order->order_number = 'ORD-' . strtoupper(Str::random(8));
            $order->save();

            $order_item = new OrderItem();
            $order_item->user_id = Auth::id();
            $order_item->order_id = $order->id;
            $order_item->product_id = $product->id;
            $order_item->size_variant_id = $size_variant_id;
            $order_item->color_variant_id = $color_variant_id;
            $order_item->quantity = 1;
            $order_item->save();
        }

        session()->flash('success', 'Product added to cart.');
        return redirect()->route('public.cart');
    }

    public function updateQuantity($orderItemId, $action)
    {
        $orderItem = OrderItem::find($orderItemId);
        if (!$orderItem || $orderItem->user_id !== Auth::id()) {
            session()->flash('error', 'Invalid order item.');
            return;
        }

        $product = $orderItem->product;
        if ($action === 'increment' && $orderItem->quantity < $product->stock) {
            $orderItem->quantity += 1;
        } elseif ($action === 'decrement' && $orderItem->quantity > 1) {
            $orderItem->quantity -= 1;
        }
        $orderItem->save();

        $this->order = Order::where('user_id', Auth::id())->where('isOrdered', false)->first();
    }

    public function removeItem($orderItemId)
    {
        $orderItem = OrderItem::find($orderItemId);
        if ($orderItem && $orderItem->user_id === Auth::id()) {
            $orderItem->delete();
            session()->flash('success', 'Item removed from cart.');
        } else {
            session()->flash('error', 'Invalid order item.');
        }

        $this->order = Order::where('user_id', Auth::id())->where('isOrdered', false)->first();
    }

    public function saveForLater($orderItemId)
    {
        // Assuming a SavedForLater model exists to store items
        $orderItem = OrderItem::find($orderItemId);
        if ($orderItem && $orderItem->user_id === Auth::id()) {
            // Move to SavedForLater table (example logic)
            \App\Models\SavedForLater::create([
                'user_id' => Auth::id(),
                'product_id' => $orderItem->product_id,
                'size_variant_id' => $orderItem->size_variant_id,
                'color_variant_id' => $orderItem->color_variant_id,
                'quantity' => $orderItem->quantity,
            ]);
            $orderItem->delete();
            session()->flash('success', 'Item saved for later.');
        } else {
            session()->flash('error', 'Invalid order item.');
        }

        $this->order = Order::where('user_id', Auth::id())->where('isOrdered', false)->first();
    }

    public function placeOrder()
    {
        $order = Order::where('user_id', Auth::id())->where('isOrdered', false)->first();
        if (!$order || !$order->orderItems || $order->orderItems->count() === 0) {
            session()->flash('error', 'Your cart is empty.');
            return;
        }

        foreach ($order->orderItems as $item) {
            $product = $item->product;
            if ($item->quantity > $product->stock) {
                session()->flash('error', "Not enough stock for {$product->name}.");
                return;
            }
            $product->stock -= $item->quantity;
            $product->save();
        }

        $order->isOrdered = true;
        $order->ordered_at = now();
        $order->save();

        session()->flash('success', 'Order placed successfully.');
        return redirect()->route('order.confirmation'); // Assuming a confirmation route exists
    }

    public function render()
    {
        $this->order = Order::where('user_id', Auth::id())->where('isOrdered', false)->first();
        return view('livewire.public.cart', ['order' => $this->order]);
    }
}