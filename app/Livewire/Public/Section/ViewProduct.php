<?php

namespace App\Livewire\Public\Section;

use App\Models\ProductVariantCombination;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Product;
use App\Services\CartService;

class ViewProduct extends Component
{
    public $product;
    public $slug;
    public $relatedProducts;
    public $quantity = 1;
    public $selectedVariants = [];
    public $availableVariants = [];
    public $selectedVariantCombination = null;
    public $whatsappNumber;
    public $customizationMessage;
    public $VariantSize = [];
    public $colorImages = [];

    public function mount($slug)
    {
        $this->slug = $slug;

        // Load product with related data
        $this->product = Product::with(['category', 'images', 'variants'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Load related products
        $this->relatedProducts = Product::where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->with('images')
            ->take(5)
            ->get();

        // Load variant combinations
        $this->VariantSize = ProductVariantCombination::where('product_id', $this->product->id)->get();

        // Extract available variant types and values
        $this->availableVariants = $this->extractVariantOptions();

        // Initialize colorImages
        $this->colorImages = $this->extractColorImages();

        // Initialize with product details (no default variant)
        $this->selectedVariantCombination = null;
        $this->selectedVariants = [];

        $this->whatsappNumber = env('WHATSAPP_NUMBER', '+1234567890');
        $this->customizationMessage = env('WHATSAPP_CUSTOMIZATION_MESSAGE', 'Hi! I\'m interested in customizing this product:');

        \Log::info('Mounted ViewProduct', [
            'slug' => $slug,
            'colorImages' => $this->colorImages,
            'availableVariants' => $this->availableVariants
        ]);

        // Dispatch initial event to set up images
        $this->dispatch('variantUpdated', ['image' => null]);
    }

    private function extractVariantOptions()
    {
        $variants = [];
        foreach ($this->VariantSize as $combination) {
            $values = is_string($combination->variant_values)
                ? json_decode($combination->variant_values, true) ?? []
                : $combination->variant_values;

            if (!is_array($values)) {
                \Log::warning('Invalid variant_values:', ['combination_id' => $combination->id]);
                continue;
            }

            foreach ($values as $type => $value) {
                if (!isset($variants[$type])) {
                    $variants[$type] = [];
                }
                if (!in_array($value, $variants[$type])) {
                    $variants[$type][] = $value;
                }
            }
        }

        foreach ($variants as &$values) {
            sort($values);
        }

        \Log::info('Extracted variant options:', $variants);
        return $variants;
    }

    private function extractColorImages()
    {
        $colorImages = [];
        foreach ($this->VariantSize as $combination) {
            $values = is_string($combination->variant_values)
                ? json_decode($combination->variant_values, true) ?? []
                : $combination->variant_values;

            if (!is_array($values)) {
                \Log::warning('Invalid variant_values in extractColorImages:', ['combination_id' => $combination->id]);
                continue;
            }

            if (isset($values['Color']) && !empty($combination->image)) {
                $colorImages[$values['Color']] = $combination->image;
            }
        }

        \Log::info('Extracted color images:', $colorImages);
        return $colorImages;
    }

    public function getAvailableValuesForType($type)
    {
        $available = [];
        foreach ($this->VariantSize as $combination) {
            $values = is_string($combination->variant_values)
                ? json_decode($combination->variant_values, true) ?? []
                : $combination->variant_values;

            if (!is_array($values)) {
                \Log::warning('Invalid variant_values in getAvailableValuesForType:', ['combination_id' => $combination->id]);
                continue;
            }

            if (isset($values[$type])) {
                $isValid = true;
                foreach ($this->selectedVariants as $selType => $selValue) {
                    if ($selType !== $type && (!isset($values[$selType]) || $values[$selType] !== $selValue)) {
                        $isValid = false;
                        break;
                    }
                }
                if ($isValid && !in_array($values[$type], $available)) {
                    $available[] = $values[$type];
                }
            }
        }

        sort($available);
        return $available;
    }

    public function selectVariant($type, $value)
    {
        if (json_decode($value, true) !== null) {
            \Log::error('Invalid variant value received:', ['type' => $type, 'value' => $value]);
            $this->dispatch('notify', ['message' => 'Invalid variant selected.', 'type' => 'error']);
            return;
        }

        $this->selectedVariants[$type] = $value;
        $this->selectedVariantCombination = null;

        foreach ($this->VariantSize as $combination) {
            $values = is_string($combination->variant_values)
                ? json_decode($combination->variant_values, true) ?? []
                : $combination->variant_values;

            if (!is_array($values)) {
                \Log::warning('Invalid variant_values in selectVariant:', ['combination_id' => $combination->id]);
                continue;
            }

            $matches = true;
            foreach ($this->selectedVariants as $selType => $selValue) {
                if (!isset($values[$selType]) || $values[$selType] !== $selValue) {
                    $matches = false;
                    break;
                }
            }

            if ($matches) {
                $this->selectedVariantCombination = $combination;
                break;
            }
        }

        \Log::info('Selected variant:', [
            'type' => $type,
            'value' => $value,
            'selectedVariants' => $this->selectedVariants,
            'selectedVariantCombination' => $this->selectedVariantCombination ? $this->selectedVariantCombination->toArray() : null
        ]);

        if ($this->selectedVariantCombination) {
            $this->dispatch('variantUpdated', ['image' => $this->selectedVariantCombination->image ? asset($this->selectedVariantCombination->image) : null]);
        } else {
            $this->dispatch('notify', ['message' => 'Selected variant combination not available.', 'type' => 'warning']);
            $this->dispatch('variantUpdated', ['image' => null]);
        }
    }

    public function increment()
    {
        $this->quantity++;
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart($productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to add items to your cart.');
        }

        if ($this->product->variants()->exists() && !$this->selectedVariantCombination) {
            $this->dispatch('notify', ['message' => 'Please select a variant before adding to cart.', 'type' => 'error']);
            return redirect()->route('public.product.view', $this->slug)
                ->with('error', 'Please select a variant before adding to cart.');
        }

        $cartService = app(CartService::class);
        $productVariantCombinationId = $this->selectedVariantCombination ? $this->selectedVariantCombination->id : null;

        if ($productVariantCombinationId && !$this->selectedVariantCombination->stock) {
            $this->dispatch('notify', ['message' => 'Selected variant is out of stock.', 'type' => 'error']);
            return redirect()->route('public.product.view', $this->slug)
                ->with('error', 'Selected variant is out of stock.');
        }

        $result = $cartService->addToCart($productId, $this->quantity, $productVariantCombinationId);

        if ($result['success']) {
            $this->dispatch('notify', ['message' => $result['message'], 'type' => 'success']);
            $this->dispatch('cartUpdated');
            return redirect()->route('myCart')->with('success', $result['message']);
        }

        return redirect()->to($result['redirect'] ?? route('public.product.view', $this->slug))
            ->with('error', $result['message']);
    }

    public function buyNow()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to proceed with Buy Now.');
        }

        $productVariantCombinationId = $this->selectedVariantCombination ? $this->selectedVariantCombination->id : null;

        if ($productVariantCombinationId && !$this->selectedVariantCombination->stock) {
            $this->dispatch('notify', ['message' => 'Selected variant is out of stock.', 'type' => 'error']);
            return redirect()->route('public.product.view', $this->slug)
                ->with('error', 'Selected variant is out of stock.');
        }

        $price = $this->selectedVariantCombination
            ? ($this->selectedVariantCombination->price ?? $this->product->price)
            : $this->product->price;

        $discountPrice = $this->selectedVariantCombination
            ? ($this->selectedVariantCombination->price ?? $this->product->discount_price)
            : ($this->product->discount_price ?? $this->product->price);

        $totalAmount = $discountPrice * $this->quantity;

        $variantDetails = [];
        if ($this->selectedVariantCombination) {
            $variantValues = is_string($this->selectedVariantCombination->variant_values)
                ? json_decode($this->selectedVariantCombination->variant_values, true)
                : $this->selectedVariantCombination->variant_values;

            if (is_array($variantValues)) {
                foreach ($variantValues as $type => $value) {
                    $variantDetails[] = [
                        'type' => $type,
                        'value' => $value
                    ];
                }
            }
        }

        session()->put('pending_order', [
            'cartItems' => [
                [
                    'product_id' => $this->product->id,
                    'product_variant_combination_id' => $productVariantCombinationId,
                    'variant_details' => $variantDetails,
                    'quantity' => $this->quantity,
                    'product' => [
                        'name' => $this->product->name,
                        'price' => $price,
                        'discount_price' => $discountPrice,
                        'image' => $this->selectedVariantCombination && $this->selectedVariantCombination->image
                            ? $this->selectedVariantCombination->image
                            : ($this->product->images->first()?->image_path ?? asset('images/placeholder.jpg')),
                    ]
                ]
            ],
            'total_amount' => $totalAmount,
            'user_email' => Auth::user()->email,
            'address_id' => null,
        ]);

        return redirect()->route('myOrder');
    }

    public function getCustomizationWhatsappUrl($productName, $orderNumber = null)
    {
        $message = $this->customizationMessage . " " . $productName;
        if ($orderNumber) {
            $message .= " (Order: " . $orderNumber . ")";
        }
        $message .= " - " . url()->current();
        $encodedMessage = urlencode($message);
        return "https://wa.me/" . str_replace(['+', ' ', '-'], '', $this->whatsappNumber) . "?text=" . $encodedMessage;
    }

    public function render()
    {
        $price = $this->product->price;
        $discountPrice = $this->product->discount_price;
        $regularPrice = $this->product->price;

        if ($this->selectedVariantCombination) {
            $price = $this->selectedVariantCombination->price ?? $this->product->price;
            $discountPrice = $this->selectedVariantCombination->price ?? $this->product->discount_price;
            if ($this->selectedVariantCombination->price) {
                $regularPrice = $this->product->price;
            }
        }

        $hasDiscount = $discountPrice && $discountPrice < $regularPrice;
        $savingAmount = $hasDiscount ? $regularPrice - $discountPrice : 0;
        $savingPercentage = $hasDiscount ? round(($savingAmount / $regularPrice) * 100) : 0;
        $finalPrice = $hasDiscount ? $discountPrice : $price;

        return view('livewire.public.section.view-product', [
            'price' => $finalPrice,
            'hasDiscount' => $hasDiscount,
            'savingAmount' => $savingAmount,
            'savingPercentage' => $savingPercentage,
            'regularPrice' => $regularPrice,
            'sku' => $this->selectedVariantCombination
                ? ($this->selectedVariantCombination->sku ?? $this->product->sku)
                : $this->product->sku,
            'deliveryCharge' => $this->product->delivery_charge ?? 0,
            'quantity' => $this->quantity,
            'availableVariants' => is_array($this->availableVariants) ? $this->availableVariants : [],
            'selectedVariants' => $this->selectedVariants,
            'selectedVariantCombination' => $this->selectedVariantCombination,
            'colorImages' => $this->colorImages,
        ]);
    }
}