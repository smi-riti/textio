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
    public $selectedVariants = []; // e.g., ['Size' => 'M', 'Color' => 'Black']
    public $availableVariants = []; // e.g., ['Size' => ['M', 'L'], 'Color' => ['Black', 'White']]
    public $selectedVariantCombination = null; // Current variant combination
    public $whatsappNumber;
    public $customizationMessage;
    public $VariantSize = []; // To match your original code

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

        // Initialize with product details (no default variant)
        $this->selectedVariantCombination = null;
        $this->selectedVariants = [];

        $this->whatsappNumber = env('WHATSAPP_NUMBER', '+1234567890');
        $this->customizationMessage = env('WHATSAPP_CUSTOMIZATION_MESSAGE', 'Hi! I\'m interested in customizing this product:');

        // Dispatch initial event to set up images
        $this->dispatch('variantUpdated');
    }

    /**
     * Extract all variant types and their unique values from variant combinations.
     */
    private function extractVariantOptions()
    {
        $variants = [];
        foreach ($this->VariantSize as $combination) {
            $values = $combination->variant_values;

            // Handle different types of variant_values
            if (is_string($values)) {
                // Check if it's valid JSON
                $decoded = json_decode($values, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $values = $decoded;
                } else {
                    // Treat as single value
                    $values = ['value' => $values];
                }
            } elseif (!is_array($values)) {
                \Log::warning('Invalid variant_values:', ['value' => $values, 'combination_id' => $combination->id]);
                continue; // Skip invalid data
            }

            // Process each key-value pair in variant_values
            foreach ($values as $type => $value) {
                if (!isset($variants[$type])) {
                    $variants[$type] = [];
                }
                if (!in_array($value, $variants[$type])) {
                    $variants[$type][] = $value;
                }
            }
        }

        // Sort values for consistency
        foreach ($variants as &$values) {
            sort($values);
        }

        return $variants;
    }

    /**
     * Get available values for a specific variant type based on current selections.
     */
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

            // Check if this combination includes the specified type
            if (isset($values[$type])) {
                $isValid = true;
                // Ensure other selected variants match this combination
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

    /**
     * Handle variant selection.
     */
    public function selectVariant($type, $value)
    {
        // Validate inputs to prevent JSON strings
        if (json_decode($value, true) !== null) {
            \Log::error('Invalid variant value received:', ['type' => $type, 'value' => $value]);
            $this->dispatch('notify', ['message' => 'Invalid variant selected.', 'type' => 'error']);
            return;
        }

        // Update selected variants
        $this->selectedVariants[$type] = $value;

        // Manually filter variant combinations (more reliable than JSON queries)
        $this->selectedVariantCombination = null;

        foreach ($this->VariantSize as $combination) {
            $values = is_string($combination->variant_values)
                ? json_decode($combination->variant_values, true) ?? []
                : $combination->variant_values;

            if (!is_array($values))
                continue;

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

        // If no match, show warning
        if (!$this->selectedVariantCombination) {
            $this->dispatch('notify', ['message' => 'Selected variant combination not available.', 'type' => 'warning']);
        }

        // Dispatch event to refresh UI
        $this->dispatch('variantUpdated');
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

    // Check if the product has variants and if a variant is required
    if ($this->product->variants()->exists() && !$this->selectedVariantCombination) {
        $this->dispatch('notify', ['message' => 'Please select a variant before adding to cart.', 'type' => 'error']);
        return redirect()->route('public.product.view', $this->slug)
            ->with('error', 'Please select a variant before adding to cart.');
    }

    $cartService = app(CartService::class);
    $productVariantCombinationId = $this->selectedVariantCombination ? $this->selectedVariantCombination->id : null;

    // Check stock for selected variant (if applicable)
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

    // Calculate prices
    $price = $this->selectedVariantCombination
        ? ($this->selectedVariantCombination->price ?? $this->product->price)
        : ($this->product->price);
    
    $discountPrice = $this->selectedVariantCombination
        ? ($this->selectedVariantCombination->price ?? $this->product->discount_price)
        : ($this->product->discount_price ?? $this->product->price);

    $totalAmount = $discountPrice * $this->quantity;

    // Extract variant details if available
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
    // Calculate price based on selected variant or product default
    $price = $this->product->price;
    $discountPrice = $this->product->discount_price;
    $regularPrice = $this->product->price;
    
    if ($this->selectedVariantCombination) {
        $price = $this->selectedVariantCombination->price ?? $this->product->price;
        $discountPrice = $this->selectedVariantCombination->price ?? $this->product->discount_price;
        
        // If variant has a price, use product price as regular price for comparison
        // Or if you have variant-specific regular prices, use those
        if ($this->selectedVariantCombination->price) {
            $regularPrice = $this->product->price; // Use product base price as regular price
        } else {
            $regularPrice = $this->product->price;
        }
    }
    
    // Calculate discount information
    $hasDiscount = $discountPrice && $discountPrice < $regularPrice;
    $savingAmount = $hasDiscount ? $regularPrice - $discountPrice : 0;
    $savingPercentage = $hasDiscount ? round(($savingAmount / $regularPrice) * 100) : 0;
    
    // Apply discount if available
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
    ]);
}
}