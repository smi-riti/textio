<?php

namespace App\Livewire\Public\Section;

use App\Models\Product;
use App\Services\CartService;
use Livewire\Component;

class AddToCart extends Component
{
    public $productId;
    public $quantity = 1;
    public $productVariantId = null;
    public $message = '';
    public $success = false;

    protected $listeners = ['updateVariant' => 'setVariant'];

    public function mount($productId, $productVariantId = null)
    {
        $this->productId = $productId;
        $this->productVariantId = $productVariantId;
    }

    public function setVariant($variantId)
    {
        $this->productVariantId = $variantId;
    }


    public function addToCart(CartService $cartService)
    {
        $result = $cartService->addToCart($this->productId, $this->quantity, $this->productVariantId);

        if (!$result['success']) {
            return redirect()->to($result['redirect'])->with('error', $result['message']);
        }

        $this->success = true;
        $this->message = $result['message'];
        $this->dispatch('cartUpdated');
        $product = Product::where('id', $this->productId)->select('slug')->first();
        return redirect()->route('public.cart', ['slug' => $product->slug])
            ->with('success', $result['message']);
    }
    public function render()
    {
        return view('livewire.public.section.add-to-cart');
    }
}
