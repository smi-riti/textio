<?php

namespace App\Livewire\Public\Section\Review;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductReview;
use Illuminate\Container\Attributes\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ReviewWork extends Component
{

    public $slug;

    #[Validate('required|integer|between:1,5')]
    public $rating;

    #[Validate('nullable|string|max:500')]
    public $review;

    public $product;


    public function mount()
    {
        $this->product = Product::where('slug', $this->slug)->firstOrFail();

    }


    public function save()
    {
        $this->validate();
        ProductReview::create([
            'product_id' => Product::where('slug', $this->slug)->first()->id,
            'user_id' => auth()->id(),
            'review' => $this->review,
            'rating' => $this->rating,
        ]);
        session()->flash('message', 'Review submitted successfully!');
        $this->reset(['review', 'rating']);
    }




    public function render()
    {
        return view('livewire.public.section.review.review-work');
    }
}
