<?php

namespace App\Livewire\Public\Section;

use App\Models\ProductReview;
use App\Models\ProductVariantCombination;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Support\Facades\Log;

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

    public $approvedReviews;

    public $reviewCount;

    public function mount($slug)
    {
        $this->slug = $slug;

        // Load product with related data
        $this->product = Product::with(['category', 'images', 'variants.images'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Load related products
        $this->relatedProducts = Product::where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->with('images')
            ->take(5)
            ->get();

        // Load variant combinations with images
        $this->variantCombinations = ProductVariantCombination::with('images')
            ->where('product_id', $this->product->id)
            ->get();

        // Extract available variant types and values
        $this->availableVariants = $this->extractVariantOptions();
        $this->disabledVariants = $this->initializeDisabledVariants();

        // Extract color images for quick lookup
        $this->colorImages = $this->extractColorImages();

        // Auto-select first available variant
        if ($this->variantCombinations->isNotEmpty()) {
            $firstCombination = $this->variantCombinations->first();
            $variantValues = $this->parseVariantValues($firstCombination->variant_values);

            if (is_array($variantValues) && !empty($variantValues)) {
                $this->selectedVariants = $variantValues;
                $this->selectedVariantCombination = $firstCombination;

                Log::info('Auto-selected first variant:', [
                    'combination_id' => $firstCombination->id,
                    'variant_values' => $variantValues
                ]);
            }

            $this->approvedReviews = $this->product->reviews()->where('approved', true)
            ->with('user')
            ->get();

           
           
        }

        

        $this->whatsappNumber = env('WHATSAPP_NUMBER', '+1234567890');
        $this->customizationMessage = env('WHATSAPP_CUSTOMIZATION_MESSAGE', 'Hi! I\'m interested in customizing this product:');

        Log::info('Mounted ViewProduct', [
            'slug' => $slug,
            'colorImages' => $this->colorImages,
            'availableVariants' => $this->availableVariants,
            'disabledVariants' => $this->disabledVariants,
            'selectedVariants' => $this->selectedVariants,
            'selectedVariantCombination' => $this->selectedVariantCombination ? $this->selectedVariantCombination->id : null
        ]);

        // Dispatch initial variant image and gallery
        $initialImage = null;
        $initialGallery = [];

        if ($this->selectedVariantCombination) {
            $initialImage = $this->getPrimaryImageForVariant($this->selectedVariantCombination);
            $initialGallery = $this->getGalleryImagesForVariant($this->selectedVariantCombination);
        }

        // Ensure images are properly formatted
        $initialImage = $this->ensureImageUrl($initialImage);
        $initialGallery = array_map([$this, 'ensureImageUrl'], $initialGallery);

        Log::info('Initial variant images:', [
            'primary' => $initialImage,
            'gallery' => $initialGallery
        ]);

        $this->dispatch('variantUpdated', [
            'image' => $initialImage,
            'galleryImages' => $initialGallery,
            'variantId' => $this->selectedVariantCombination ? $this->selectedVariantCombination->id : null
        ]);
    }

    private function getPrimaryImageForVariant($variantCombination)
    {
        if (!$variantCombination)
            return null;
        return $variantCombination->primary_image; // Uses accessor from ProductVariantCombination
    }

    private function getGalleryImagesForVariant($variantCombination)
    {
        if (!$variantCombination)
            return [];
        return $variantCombination->gallery_images->pluck('image_path')->toArray();
    }

    private function extractVariantOptions()
    {
        $variants = [];
        foreach ($this->variantCombinations as $combination) {
            $values = $this->parseVariantValues($combination->variant_values);
            if (!is_array($values)) {
                Log::warning('Invalid variant_values:', ['combination_id' => $combination->id]);
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
            if (!is_array($values) || !isset($values['Color']))
                continue;

            $color = $values['Color'];
            $primaryImage = $this->getPrimaryImageForVariant($combination);

            if ($color && $primaryImage) {
                // Ensure image URL is properly formatted
                $primaryImage = $this->ensureImageUrl($primaryImage);
                $colorImages[$color] = $primaryImage;

                Log::info("Found color image:", [
                    'color' => $color,
                    'image' => $primaryImage
                ]);
            }
        }

        Log::info('Extracted all color images:', $colorImages);
        return $colorImages;
    }

    private function ensureImageUrl($imagePath)
    {
        if (!$imagePath) {
            return null;
        }

        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }

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
            Log::error('Invalid variant value received:', ['type' => $type, 'value' => $value]);
            $this->dispatch('notify', ['message' => 'Invalid variant selected.', 'type' => 'error']);
            return;
        }

        Log::info('Selecting variant:', ['type' => $type, 'value' => $value]);

        $this->selectedVariants[$type] = $value;
        $this->selectedVariantCombination = null;

        // If it's a color variant, get the image directly from colorImages
        $variantImage = null;
        if ($type === 'Color' && isset($this->colorImages[$value])) {
            $variantImage = $this->colorImages[$value];
            Log::info('Found color variant image:', ['color' => $value, 'image' => $variantImage]);
        }

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

        $this->updateDisabledVariants();

        Log::info('Selected variant:', [
            'type' => $type,
            'value' => $value,
            'selectedVariants' => $this->selectedVariants,
            'selectedVariantCombination' => $this->selectedVariantCombination ? $this->selectedVariantCombination->toArray() : null,
            'disabledVariants' => $this->disabledVariants
        ]);

        if ($this->selectedVariantCombination) {
            $primaryImage = $this->getPrimaryImageForVariant($this->selectedVariantCombination);
            $galleryImages = $this->getGalleryImagesForVariant($this->selectedVariantCombination);

            // If this is a color variant, use the color image as primary
            if ($type === 'Color' && !empty($this->colorImages[$value])) {
                $primaryImage = $this->colorImages[$value];
            }

            // Ensure the image URLs are properly formatted
            $primaryImage = $this->ensureImageUrl($primaryImage);
            $galleryImages = array_map([$this, 'ensureImageUrl'], $galleryImages);

            // Log the images being dispatched
            Log::info('Dispatching variant images:', [
                'primary' => $primaryImage,
                'gallery' => $galleryImages
            ]);

            $this->dispatch('variantUpdated', [
                'image' => $primaryImage,
                'galleryImages' => $galleryImages,
                'variantId' => $this->selectedVariantCombination->id
            ]);
        } else {
            $this->dispatch('notify', ['message' => 'Selected variant combination not available.', 'type' => 'warning']);
            $this->dispatch('variantUpdated', ['image' => null, 'galleryImages' => [], 'variantId' => null]);
        }

        $this->dispatch('$refresh');
    }

    private function updateDisabledVariants()
    {
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
                        'image' => $this->selectedVariantCombination && $this->selectedVariantCombination->primary_image
                            ? $this->selectedVariantCombination->primary_image
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

        // Get variant image
        $variantImage = null;
        if ($this->selectedVariantCombination) {
            // If we have a selected color variant, use that image
            if (isset($this->selectedVariants['Color'])) {
                $colorValue = $this->selectedVariants['Color'];
                $variantImage = isset($this->colorImages[$colorValue]) ? $this->colorImages[$colorValue] : null;
            }

            // If no color image, use the variant combination's primary image
            if (!$variantImage) {
                $variantImage = $this->getPrimaryImageForVariant($this->selectedVariantCombination);
            }
        }

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

        $variantImage = $this->getPrimaryImageForVariant($currentVariant);

        $formattedProductImages = [];
        if ($currentVariant) {
            $formattedProductImages = $this->getGalleryImagesForVariant($currentVariant);
        } else {
            $formattedProductImages = $this->product->images->map(function ($image) {
                return $this->ensureImageUrl($image->image_path);
            })->toArray();
        }

        $this->hasStock = $currentVariant ? ($currentVariant->stock > 0) : ($this->product->quantity > 0 ?? true);

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
