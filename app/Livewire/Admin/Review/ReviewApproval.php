<?php

namespace App\Livewire\Admin\Review;

use App\Models\ProductReview;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ReviewApproval extends Component
{
    public $PendingReviews; // Fixed typo
    public $approvedReviews;


    #[Layout('components.layouts.admin')]
    public function mount()
    {
        $this->loadReview();
    }

    public function loadReview()
    {
        $this->PendingReviews = ProductReview::where('approved', false)
            ->with(['user:id,name'])
            ->latest()
            ->get();

        $this->approvedReviews = ProductReview::where('approved', true)
            ->with(['user:id,name'])
            ->latest()
            ->get();
    }

    public function approveReview($reviewId)
    {
        $review = ProductReview::findOrFail($reviewId);
        $review->update(['approved' => true]);
        $this->loadReview(); // Refresh the review lists
    }

   public function rejectReview($reviewId)
    {
        try {
            $review = ProductReview::findOrFail($reviewId);
            $review->delete();
            $this->loadReview(); // Refresh the review lists after deletion
            session()->flash('success', 'Review rejected and deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to reject review: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.review.review-approval');
    }
}