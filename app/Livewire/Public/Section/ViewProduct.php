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
    public $disabledVariants = [];
    public $selectedVariantCombination = null;
    public $whatsappNumber;
    public $customizationMessage;
    public $variantCombinations = [];
    public $colorImages = [];
    public $hasStock = true;

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
        $this->variantCombinations = ProductVariantCombination::where('product_id', $this->product->id)->get();

        // Extract available variant types and values
        $this->availableVariants = $this->extractVariantOptions();
        $this->disabledVariants = $this->initializeDisabledVariants();

        // Initialize colorImages
        $this->colorImages = $this->extractColorImages();

        // AUTO-SELECT FIRST AVAILABLE VARIANT
        if ($this->variantCombinations->isNotEmpty()) {
            $firstCombination = $this->variantCombinations->first();
            $variantValues = $this->parseVariantValues($firstCombination->variant_values);
            
            if (is_array($variantValues) && !empty($variantValues)) {
                $this->selectedVariants = $variantValues;
                $this->selectedVariantCombination = $firstCombination;
                
                \Log::info('Auto-selected first variant:', [
                    'combination_id' => $firstCombination->id,
                    'variant_values' => $variantValues
                ]);
            }
        }

        $this->whatsappNumber = env('WHATSAPP_NUMBER', '+1234567890');
        $this->customizationMessage = env('WHATSAPP_CUSTOMIZATION_MESSAGE', 'Hi! I\'m interested in customizing this product:');

        \Log::info('Mounted ViewProduct', [
            'slug' => $slug,
            'colorImages' => $this->colorImages,
            'availableVariants' => $this->availableVariants,
            'disabledVariants' => $this->disabledVariants,
            'selectedVariants' => $this->selectedVariants,
            'selectedVariantCombination' => $this->selectedVariantCombination ? $this->selectedVariantCombination->id : null
        ]);

        // Dispatch initial event to set up images
        $this->dispatch('variantUpdated', [
            'image' => $this->selectedVariantCombination ? $this->selectedVariantCombination->image : null,
            'variantId' => $this->selectedVariantCombination ? $this->selectedVariantCombination->id : null
        ]);
    }

    private function extractVariantOptions()
    {
        $variants = [];
        foreach ($this->variantCombinations as $combination) {
            $values = $this->parseVariantValues($combination->variant_values);
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

        return $variants;
    }

    private function extractColorImages()
    {
        $colorImages = [];
        foreach ($this->variantCombinations as $combination) {
            $values = $this->parseVariantValues($combination->variant_values);
            if (!is_array($values)) {
                continue;
            }

            if (isset($values['Color']) && !empty($combination->image)) {
                // Don't use asset() on already full URLs
                $colorImages[$values['Color']] = $combination->image;
            }
        }

        return $colorImages;
    }

    private function ensureImageUrl($imagePath)
    {
        if (!$imagePath) {
            return null;
        }

        // If it's already a full URL, return as is
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }

        // If it's a local path, use asset()
        return asset($imagePath);
    }

    private function initializeDisabledVariants()
    {
        $disabled = [];
        foreach ($this->availableVariants as $type => $values) {
            $disabled[$type] = array_fill_keys($values, false);
        }
        return $disabled;
    }

    private function parseVariantValues($variantValues)
    {
        if (is_string($variantValues)) {
            $decoded = json_decode($variantValues, true);
            return is_array($decoded) ? $decoded : [];
        }
        return is_array($variantValues) ? $variantValues : [];
    }

    public function selectVariant($type, $value)
    {
        if (json_decode($value, true) !== null) {
            \Log::error('Invalid variant value received:', ['type' => $type, 'value' => $value]);
            $this->dispatch('notify', ['message' => 'Invalid variant selected.', 'type' => 'error']);
            return;
        }

        // Update selected variant
        $this->selectedVariants[$type] = $value;
        $this->selectedVariantCombination = null;

        // Find matching combination
        foreach ($this->variantCombinations as $combination) {
            $values = $this->parseVariantValues($combination->variant_values);
            if (!is_array($values)) {
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

        // Update disabled variants
        $this->updateDisabledVariants();

        \Log::info('Selected variant:', [
            'type' => $type,
            'value' => $value,
            'selectedVariants' => $this->selectedVariants,
            'selectedVariantCombination' => $this->selectedVariantCombination ? $this->selectedVariantCombination->toArray() : null,
            'disabledVariants' => $this->disabledVariants
        ]);

        if ($this->selectedVariantCombination) {
            $variantImage = $this->selectedVariantCombination->image;

            $this->dispatch('variantUpdated', [
                'image' => $variantImage,
                'variantId' => $this->selectedVariantCombination->id
            ]);
        } else {
            $this->dispatch('notify', ['message' => 'Selected variant combination not available.', 'type' => 'warning']);
            $this->dispatch('variantUpdated', ['image' => null, 'variantId' => null]);
        }

        // Force Livewire to re-render the component
        $this->dispatch('$refresh');
    }

    private function updateDisabledVariants()
    {
        // Reset disabled variants
        $this->disabledVariants = $this->initializeDisabledVariants();

        foreach ($this->availableVariants as $type => $values) {
            foreach ($values as $value) {
                $isAvailable = false;
                foreach ($this->variantCombinations as $combination) {
                    $variantValues = $this->parseVariantValues($combination->variant_values);
                    if (!is_array($variantValues)) {
                        continue;
                    }

                    if (isset($variantValues[$type]) && $variantValues[$type] === $value) {
                        $isValid = true;
                        foreach ($this->selectedVariants as $selType => $selValue) {
                            if ($selType !== $type && (!isset($variantValues[$selType]) || $variantValues[$selType] !== $selValue)) {
                                $isValid = false;
                                break;
                            }
                        }
                        if ($isValid) {
                            $isAvailable = true;
                            break;
                        }
                    }
                }
                $this->disabledVariants[$type][$value] = !$isAvailable;
            }
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

        if (!$this->hasStock) {
            $this->dispatch('notify', ['message' => 'This product is currently unavailable.', 'type' => 'error']);
            return redirect()->route('public.product.view', $this->slug)
                ->with('error', 'This product is currently unavailable.');
        }

        $cartService = app(CartService::class);
        $productVariantCombinationId = $this->selectedVariantCombination ? $this->selectedVariantCombination->id : null;

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

        if (!$this->hasStock) {
            $this->dispatch('notify', ['message' => 'This product is currently unavailable.', 'type' => 'error']);
            return redirect()->route('public.product.view', $this->slug)
                ->with('error', 'This product is currently unavailable.');
        }

        $productVariantCombinationId = $this->selectedVariantCombination ? $this->selectedVariantCombination->id : null;

        $price = $this->selectedVariantCombination
            ? ($this->selectedVariantCombination->price ?? $this->product->price)
            : $this->product->price;

        $discountPrice = $this->selectedVariantCombination
            ? ($this->selectedVariantCombination->price ?? $this->product->discount_price)
            : ($this->product->discount_price ?? $this->product->price);

        $totalAmount = $discountPrice * $this->quantity;

        $variantDetails = [];
        if ($this->selectedVariantCombination) {
            $variantValues = $this->parseVariantValues($this->selectedVariantCombination->variant_values);
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

        // Use selected variant or first available variant
        $currentVariant = $this->selectedVariantCombination;
        
        if (!$currentVariant && $this->variantCombinations->isNotEmpty()) {
            $currentVariant = $this->variantCombinations->first();
        }

        if ($currentVariant) {
            $price = $currentVariant->price ?? $this->product->price;
            $discountPrice = $currentVariant->price ?? $this->product->discount_price;
            if ($currentVariant->price) {
                $regularPrice = $this->product->price;
            }
        }

        $hasDiscount = $discountPrice && $discountPrice < $regularPrice;
        $savingAmount = $hasDiscount ? $regularPrice - $discountPrice : 0;
        $savingPercentage = $hasDiscount ? round(($savingAmount / $regularPrice) * 100) : 0;
        $finalPrice = $hasDiscount ? $discountPrice : $price;

        // Ensure variant image URL is proper
        $variantImage = null;
        if ($currentVariant && $currentVariant->image) {
            $variantImage = $this->ensureImageUrl($currentVariant->image);
        }

        // Ensure all product images have proper URLs
        $formattedProductImages = $this->product->images->map(function($image) {
            return $this->ensureImageUrl($image->image_path);
        })->toArray();

        // Update stock availability
        $this->hasStock = $currentVariant ? ($currentVariant->stock > 0) : ($this->product->stock > 0 ?? true);

        return view('livewire.public.section.view-product', [
            'price' => $finalPrice,
            'hasDiscount' => $hasDiscount,
            'savingAmount' => $savingAmount,
            'savingPercentage' => $savingPercentage,
            'regularPrice' => $regularPrice,
            'sku' => $currentVariant
                ? ($currentVariant->sku ?? $this->product->sku)
                : $this->product->sku,
            'deliveryCharge' => $this->product->delivery_charge ?? 0,
            'quantity' => $this->quantity,
            'availableVariants' => is_array($this->availableVariants) ? $this->availableVariants : [],
            'selectedVariants' => $this->selectedVariants,
            'selectedVariantCombination' => $currentVariant,
            'colorImages' => $this->colorImages,
            'disabledVariants' => $this->disabledVariants,
            'variantImage' => $variantImage,
            'formattedProductImages' => $formattedProductImages,
            'currentVariant' => $currentVariant,
            'hasStock' => $this->hasStock,
        ]);
    }
}