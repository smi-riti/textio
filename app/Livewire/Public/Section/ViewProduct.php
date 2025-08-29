<?php

namespace App\Livewire\Public\Section;

use Livewire\Component;
use App\Models\Product;

class ViewProduct extends Component
{
    public $product;
    public $slug;
    public $relatedProducts;
    public $review;
    public $name;
    public $email;
    public $rating;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->product = Product::with(['category', 'images'])->where('slug', $slug)->firstOrFail();
        $this->relatedProducts = Product::where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->with('images')
            ->take(5)
            ->get();
    }

    public function submitReview()
    {
        $this->validate([
            'rating' => 'required|integer|between:1,5',
            'review' => 'required|string|min:10',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        // Placeholder for review submission logic
        // Example: Save review to database
        // Review::create([
        //     'product_id' => $this->product->id,
        //     'rating' => $this->rating,
        //     'review' => $this->review,
        //     'name' => $this->name,
        //     'email' => $this->email,
        // ]);

        session()->flash('message', 'Review submitted successfully!');
        $this->reset(['rating', 'review', 'name', 'email']);
    }

    public function render()
    {
        return view('livewire.public.section.view-product');
    }
}