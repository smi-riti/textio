<?php

namespace App\Livewire\Public\Section;

use Livewire\Component;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class ViewProduct extends Component
{
    public $product;
    public $slug;
    public $relatedProducts;
    public $quantity = 1;
    public $selectedColor = '';
    public $selectedStorage = '';
    public $showCartConfirmation = false;
    public $name;
    public $email;
    // public $review;
    // public $rating;

     protected $listeners = ['backToProduct' => 'backToProductFromAddItem'];

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->product = Product::with(['category', 'images', 'variants'])->where('slug', $slug)->firstOrFail();
        $this->relatedProducts = Product::where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->with('images')
            ->take(5)
            ->get();

        // Set default variant selections
        if ($this->product->variants->isNotEmpty()) {
            $colorVariant = $this->product->variants->where('type', 'color')->first();
            $storageVariant = $this->product->variants->where('type', 'storage')->first();
            if ($colorVariant) {
                $this->selectedColor = $colorVariant->value;
            }
            if ($storageVariant) {
                $this->selectedStorage = $storageVariant->value;
            }
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

    public function selectColor($color)
    {
        $this->selectedColor = $color;
    }

    public function selectStorage($storage)
    {
        $this->selectedStorage = $storage;
    }

    public function addToCart()
    {
        $this->showCartConfirmation = true;
    }

    public function backToProduct()
    {
        $this->showCartConfirmation = false;
    }

       public function backToProductFromAddItem()
    {
        $this->showCartConfirmation = false;
    }

    public function confirmAddToCart($cartItem)
    {
        if (!auth()->check()) {
            session()->flash('message', 'Please log in to add items to your cart.');
            return redirect()->route('login');
        }

        $cart = Session::get('cart', []);
        $cart[] = $cartItem;
        Session::put('cart', $cart);

        session()->flash('message', 'Product added to cart successfully!');
        $this->showCartConfirmation = false;
        return redirect()->route('public.cart');
    }

    // public function submitReview()
    // {
    //     $this->validate([
    //         'rating' => 'required|integer|between:1,5',
    //         'review' => 'required|string|min:10',
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email',
    //     ]);

    //     // Placeholder for review submission logic
    //     // Example: Save review to database
    //     // Review::create([
    //     //     'product_id' => $this->product->id,
    //     //     'rating' => $this->rating,
    //     //     'review' => $this->review,
    //     //     'name' => $this->name,
    //     //     'email' => $this->email,
    //     // ]);

    //     session()->flash('message', 'Review submitted successfully!');
    //     $this->reset(['rating', 'review', 'name', 'email']);
    // }

    public function render()
    {
        return view('livewire.public.section.view-product', [
            'price' => $this->product->price,
            'discount' => $this->product->discount_price ?? 0,
            'deliveryCharge' => $this->product->delivery_charge ?? 0,
            'quantity' => $this->quantity,
        ]);
    }
}