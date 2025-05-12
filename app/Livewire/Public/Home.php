<?php
namespace App\Livewire\Public;

use App\Models\Product;
use Livewire\Component;

class Home extends Component
{
    public $popularFilter = 'all';
    public $recentFilter = 'all';

    public function updatePopularFilter($filter)
    {
        $this->popularFilter = $filter;
    }

    public function updateRecentFilter($filter)
    {
        $this->recentFilter = $filter;
    }

    public function render()
    {
        // Fetch recent products
        $recentQuery = Product::with(['category', 'brand', 'images', 'variants'])
            ->where('status', 1);

        if ($this->recentFilter === 'new') {
            $recentQuery->where('created_at', '>=', now()->subDays(7));
        } elseif ($this->recentFilter === 'sale') {
            $recentQuery->whereNotNull('discount_price');
        }

        $recentProducts = $recentQuery->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Fetch popular products (using discount_price or random)
        $popularQuery = Product::with(['category', 'brand', 'images', 'variants'])
            ->where('status', 1);

        if ($this->popularFilter === 'new') {
            $popularQuery->where('created_at', '>=', now()->subDays(7));
        } elseif ($this->popularFilter === 'sale') {
            $popularQuery->whereNotNull('discount_price');
        } else {
            // Fallback: sort by discount_price (non-null first) or random
            $popularQuery->orderByRaw('discount_price IS NOT NULL DESC, RAND()');
        }

        $popularProducts = $popularQuery->take(10)->get();

        return view('livewire.public.home', compact('recentProducts', 'popularProducts'));
    }
}