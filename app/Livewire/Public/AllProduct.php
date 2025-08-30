<?php

namespace App\Livewire\Public;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class AllProduct extends Component
{
    use WithPagination;

    // Search
    #[Url(as: 'search')]
    public $searchQuery = '';

    public $search = '';

    // Categories and subcategories
    public $categories;
    public $parentCategories;
    public $subcategories = [];
    public $showAllCategories = false;
    
    #[Url(as: 'category')]
    public $selectedCategory = null;
    
    #[Url(as: 'subcats')]
    public $selectedSubcategories = [];

    // Temporary category selections (before apply)
    public $tempSelectedCategory = null;
    public $tempSelectedSubcategories = [];

    // Brands
    public $brands;
    public $showAllBrands = false;
    #[Url(as: 'brands')]
    public $selectedBrands = [];
    
    // Temporary brand selections (before apply)
    public $tempSelectedBrands = [];

    // Price range
    #[Url(as: 'price_min')]
    public $minPrice = 0;
    
    #[Url(as: 'price_max')]
    public $maxPrice = 10000;

    // Temporary price range (before apply)
    public $tempMinPrice = 0;
    public $tempMaxPrice = 10000;

    // Colors (static colors for now need to use database)
    public $availableColors = [
        'red' => '#ef4444',
        'blue' => '#3b82f6', 
        'green' => '#10b981',
        'yellow' => '#f59e0b',
        'purple' => '#8b5cf6',
        'pink' => '#ec4899',
        'black' => '#000000',
        'white' => '#ffffff',
        'gray' => '#6b7280',
        'orange' => '#f97316',
    ];
    
    #[Url(as: 'colors')]
    public $selectedColors = [];
    
    // Temporary color selections (before apply)
    public $tempSelectedColors = [];

    // Sizes (static size for now need to use database )
    public $availableSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
    #[Url(as: 'sizes')]
    public $selectedSizes = [];
    
    // Temporary size selections (before apply)
    public $tempSelectedSizes = [];

    // Rating filter
    #[Url(as: 'rating')]
    public $minimumRating = 0;
    
    // Temporary rating selection (before apply)
    public $tempMinimumRating = 0;

    // Sorting
    #[Url(as: 'sort')]
    public $sortBy = 'popularity';

    // Pagination
    protected $paginationTheme = 'tailwind';

    // Wishlist storage (you can enhance this with database)
    public $wishlistItems = [];

    public function mount()
    {
        $this->categories = Category::where('is_active', true)->get();
        $this->parentCategories = Category::where('is_active', true)
            ->whereNull('parent_category_id')
            ->get();
        $this->brands = Brand::where('is_active', true)->get();
        
        // Load wishlist from session if exists
        $this->wishlistItems = session()->get('wishlist', []);
        
        // Initialize temp values with current values
        $this->tempSelectedCategory = $this->selectedCategory;
        $this->tempSelectedSubcategories = $this->selectedSubcategories;
        $this->tempSelectedBrands = $this->selectedBrands;
        $this->tempSelectedColors = $this->selectedColors;
        $this->tempSelectedSizes = $this->selectedSizes;
        $this->tempMinimumRating = $this->minimumRating;
        $this->tempMinPrice = $this->minPrice;
        $this->tempMaxPrice = $this->maxPrice;
    }

    public function updated($property)
    {
        // Sync search properties
        if ($property === 'searchQuery') {
            $this->search = $this->searchQuery;
        } elseif ($property === 'search') {
            $this->searchQuery = $this->search;
        }

        // Only search and sort trigger live updates
        if (in_array($property, ['searchQuery', 'search', 'sortBy'])) {
            $this->resetPage();
        }
    }

    public function updatedTempSelectedCategory($value)
    {
        if ($value) {
            $this->subcategories = Category::where('is_active', true)
                ->where('parent_category_id', $value)
                ->get();
        } else {
            $this->subcategories = [];
        }
        $this->tempSelectedSubcategories = [];
    }

    public function toggleShowAllCategories()
    {
        $this->showAllCategories = !$this->showAllCategories;
    }

    public function toggleShowAllBrands()
    {
        $this->showAllBrands = !$this->showAllBrands;
    }

    public function toggleTempSubcategory($subcategoryId)
    {
        if (in_array($subcategoryId, $this->tempSelectedSubcategories)) {
            $this->tempSelectedSubcategories = array_diff($this->tempSelectedSubcategories, [$subcategoryId]);
        } else {
            $this->tempSelectedSubcategories[] = $subcategoryId;
        }
    }

    public function toggleTempBrand($brandId)
    {
        if (in_array($brandId, $this->tempSelectedBrands)) {
            $this->tempSelectedBrands = array_diff($this->tempSelectedBrands, [$brandId]);
        } else {
            $this->tempSelectedBrands[] = $brandId;
        }
    }

    public function toggleTempColor($color)
    {
        if (in_array($color, $this->tempSelectedColors)) {
            $this->tempSelectedColors = array_diff($this->tempSelectedColors, [$color]);
        } else {
            $this->tempSelectedColors[] = $color;
        }
    }

    public function toggleTempSize($size)
    {
        if (in_array($size, $this->tempSelectedSizes)) {
            $this->tempSelectedSizes = array_diff($this->tempSelectedSizes, [$size]);
        } else {
            $this->tempSelectedSizes[] = $size;
        }
    }

    public function setTempRating($rating)
    {
        $this->tempMinimumRating = $rating;
    }

    public function resetFilters()
    {
        $this->searchQuery = '';
        $this->search = '';
        $this->selectedCategory = null;
        $this->selectedSubcategories = [];
        $this->selectedBrands = [];
        $this->minPrice = 0;
        $this->maxPrice = 10000;
        $this->selectedColors = [];
        $this->selectedSizes = [];
        $this->minimumRating = 0;
        $this->sortBy = 'popularity';
        $this->subcategories = [];
        
        // Reset temp values
        $this->tempSelectedCategory = null;
        $this->tempSelectedSubcategories = [];
        $this->tempSelectedBrands = [];
        $this->tempSelectedColors = [];
        $this->tempSelectedSizes = [];
        $this->tempMinimumRating = 0;
        $this->tempMinPrice = 0;
        $this->tempMaxPrice = 10000;
        
        $this->resetPage();
    }

    public function addToWishlist($productId)
    {
        // Logic for Wishlist
    }

    public function removeFromWishlist($productId)
    {
        // Logic for Remove Wishlist
    }

    public function addToCart($productId)
    {
        // Logic for Cart
    }

    public function getHasActiveFiltersProperty()
    {
        return $this->searchQuery || 
               $this->search ||
               $this->selectedCategory || 
               !empty($this->selectedSubcategories) ||
               !empty($this->selectedBrands) ||
               $this->minPrice > 0 || 
               $this->maxPrice < 10000 ||
               !empty($this->selectedColors) ||
               !empty($this->selectedSizes) ||
               $this->minimumRating > 0;
    }

    public function applyPriceFilter($minPrice, $maxPrice)
    {
        $this->minPrice = $minPrice;
        $this->maxPrice = $maxPrice;
        $this->resetPage();
    }

    public function applyAllFilters()
    {
        $this->selectedCategory = $this->tempSelectedCategory;
        $this->selectedSubcategories = $this->tempSelectedSubcategories;
        $this->selectedBrands = $this->tempSelectedBrands;
        $this->selectedColors = $this->tempSelectedColors;
        $this->selectedSizes = $this->tempSelectedSizes;
        $this->minimumRating = $this->tempMinimumRating;
        $this->minPrice = $this->tempMinPrice;
        $this->maxPrice = $this->tempMaxPrice;
        $this->resetPage();
        $this->dispatch('close-mobile-filters');
    }

    public function render()
    {
        $query = Product::query()
            ->where('status', true)
            ->with(['category', 'brand', 'images', 'variants']);

        // Apply search filter
        if ($this->searchQuery || $this->search) {
            $searchTerm = $this->searchQuery ?: $this->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('sku', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('category', function ($subq) use ($searchTerm) {
                      $subq->where('title', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('brand', function ($subq) use ($searchTerm) {
                      $subq->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Apply category filter
        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        // Apply subcategory filter
        if (!empty($this->selectedSubcategories)) {
            $query->whereIn('category_id', $this->selectedSubcategories);
        }

        // Apply brand filter
        if (!empty($this->selectedBrands)) {
            $query->whereIn('brand_id', $this->selectedBrands);
        }

        // Apply price filter
        if ($this->minPrice > 0 || $this->maxPrice < 10000) {
            $query->where(function ($q) {
                $q->whereBetween('discount_price', [$this->minPrice, $this->maxPrice])
                  ->orWhere(function ($subq) {
                      $subq->whereNull('discount_price')
                           ->whereBetween('price', [$this->minPrice, $this->maxPrice]);
                  });
            });
        }

        // Apply color filter (assuming you have color variants or color field)
        if (!empty($this->selectedColors)) {
            $query->whereHas('variants', function ($q) {
                $q->whereIn('variant_name', $this->selectedColors);
            });
        }

        // Apply size filter (assuming you have size variants)
        if (!empty($this->selectedSizes)) {
            $query->whereHas('variants', function ($q) {
                $q->whereIn('variant_name', $this->selectedSizes);
            });
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'price_low':
                $query->orderByRaw('COALESCE(discount_price, price) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(discount_price, price) DESC');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'discount':
                $query->orderByRaw('((price - COALESCE(discount_price, price)) / price) DESC');
                break;
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popularity':
            default:
                // For popularity, you might want to order by number of orders or reviews
                $query->withCount('orderItems')->orderBy('order_items_count', 'desc');
                break;
        }

        $products = $query->paginate(12);

        return view('livewire.public.all-product', [
            'products' => $products,
            'hasActiveFilters' => $this->hasActiveFilters,
        ]);
    }
}
