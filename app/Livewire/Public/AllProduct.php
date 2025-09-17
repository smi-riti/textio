<?php

namespace App\Livewire\Public;

use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductVariantCombination;
use App\Services\WishlistService;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;

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

    // Colors and Sizes (dynamic from DB)
    public $availableColors = [];
    #[Url(as: 'colors')]
    public $selectedColors = [];
    public $tempSelectedColors = [];

    public $availableSizes = [];
    #[Url(as: 'sizes')]
    public $selectedSizes = [];
    public $tempSelectedSizes = [];

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

        // NEW: Resolve category_slug from URL to category ID
        if (request()->has('category_slug')) {
            $category = Category::where('slug', request('category_slug'))
                ->where('is_active', true)
                ->first();
            if ($category) {
                $this->selectedCategory = $category->id;
            }
        }

        // Initialize subcategories if category is pre-selected (now handles resolved ID)
        if ($this->selectedCategory) {
            $this->subcategories = Category::where('parent_category_id', $this->selectedCategory)
                ->where('is_active', true)
                ->get();
        }

        // Initialize temporary filters with current values
        $this->tempSelectedCategory = $this->selectedCategory;
        $this->tempSelectedSubcategories = $this->selectedSubcategories;
        $this->tempSelectedBrands = $this->selectedBrands;
        $this->tempMinPrice = $this->minPrice;
        $this->tempMaxPrice = $this->maxPrice;
        $this->tempSelectedColors = $this->selectedColors;
        $this->tempSelectedSizes = $this->selectedSizes;

        // Fetch available colors and sizes from database
        $this->fetchAvailableColors();
        $this->fetchAvailableSizes();
    }

    protected function fetchAvailableColors()
    {
        // Get all variant combinations and extract colors from variant_values JSON
        $colors = ProductVariantCombination::whereNotNull('variant_values')
            ->get()
            ->map(function ($combination) {
                // Decode the JSON string to array
                $values = json_decode($combination->variant_values, true);
                if (!is_array($values)) return null;
                
                // Look for Color/color keys in the variant_values array
                return $values['Color'] ?? $values['color'] ?? null;
            })
            ->filter() // Remove null values
            ->unique()
            ->sort()
            ->values()
            ->toArray();
        
        $this->availableColors = $colors;
        
        // Debug: Log the colors found
        \Log::info('Available colors fetched:', $colors);
    }

    protected function fetchAvailableSizes()
    {
        // Get all variant combinations and extract sizes from variant_values JSON
        $sizes = ProductVariantCombination::whereNotNull('variant_values')
            ->get()
            ->map(function ($combination) {
                // Decode the JSON string to array
                $values = json_decode($combination->variant_values, true);
                if (!is_array($values)) return null;
                
                // Look for Size/size keys in the variant_values array
                return $values['Size'] ?? $values['size'] ?? null;
            })
            ->filter() // Remove null values
            ->unique()
            ->sort()
            ->values()
            ->toArray();
        
        $this->availableSizes = $sizes;
        
        // Debug: Log the sizes found
        \Log::info('Available sizes fetched:', $sizes);
    }

    public function updated($property)
    {
        // Sync search properties
        if ($property === 'searchQuery') {
            $this->search = $this->searchQuery;
        }

        // If category is updated, reset subcategories
        if ($property === 'tempSelectedCategory') {
            $this->tempSelectedSubcategories = [];
        }
        // Do NOT auto-apply filters on every change. Only apply on Apply Filters button.
    }

    public function updatedTempSelectedCategory($value)
    {
        // When a parent category is selected, fetch its subcategories
        if ($value) {
            $this->subcategories = Category::where('parent_category_id', $value)
                ->where('is_active', true)
                ->get();
        } else {
            $this->subcategories = collect(); // Use empty collection
        }
        
        // Reset subcategory selection when parent category changes
        $this->tempSelectedSubcategories = [];
        
        // Refresh available colors and sizes based on new category
        $this->refreshVariantFilters();
    }

    protected function refreshVariantFilters()
    {
        // Re-fetch colors and sizes based on current filter selections
        $this->fetchAvailableColors();
        $this->fetchAvailableSizes();
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
        $this->sortBy = 'popularity';
        $this->subcategories = collect();

        // Reset temp values
        $this->tempSelectedCategory = null;
        $this->tempSelectedSubcategories = [];
        $this->tempSelectedBrands = [];
        $this->tempSelectedColors = [];
        $this->tempSelectedSizes = [];
        $this->tempMinPrice = 0;
        $this->tempMaxPrice = 10000;

        // Refresh variant filters
        $this->refreshVariantFilters();
        
        $this->resetPage();
        $this->dispatch('close-mobile-filters');
    }

    public function addToWishlist($productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $wishlistService = app(WishlistService::class);
        $result = $wishlistService->addToWishlist($productId);

        if ($result['success']) {
            $this->dispatch('wishlistUpdated');
            session()->flash('wishlist_message', $result['message']);
            
            // Update local wishlist items for UI
            if (!in_array($productId, $this->wishlistItems)) {
                $this->wishlistItems[] = $productId;
            }
        } else {
            if (isset($result['redirect'])) {
                return redirect()->to($result['redirect']);
            }
            session()->flash('wishlist_error', $result['message']);
        }
    }

    public function removeFromWishlist($productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $wishlistService = app(WishlistService::class);
        $result = $wishlistService->removeFromWishlist($productId);

        if ($result['success']) {
            $this->dispatch('wishlistUpdated');
            session()->flash('wishlist_message', $result['message']);
            
            // Update local wishlist items for UI
            $this->wishlistItems = array_diff($this->wishlistItems, [$productId]);
        } else {
            session()->flash('wishlist_error', $result['message']);
        }
    }

    public function addToCart($productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $product = Product::find($productId);
        if (!$product) {
            session()->flash('error', 'Product not found.');
            return;
        }

        $cartService = app(CartService::class);
        $result = $cartService->addToCart($productId, 1); // Default quantity 1

        if ($result['success']) {
            $this->dispatch('cartUpdated');
            session()->flash('message', $result['message']);
        } else {
            if (isset($result['redirect'])) {
                return redirect()->to($result['redirect']);
            }
            session()->flash('error', $result['message']);
        }
    }

    public function getHasActiveFiltersProperty()
    {
        return $this->searchQuery ||
            !empty($this->selectedSubcategories) || !empty($this->selectedBrands) ||
            ($this->minPrice > 0 || $this->maxPrice < 10000) || !empty($this->selectedColors) || !empty($this->selectedSizes) || $this->selectedCategory;
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
        $this->minPrice = $this->tempMinPrice;
        $this->maxPrice = $this->tempMaxPrice;
        
        // Update subcategories for the selected category
        if ($this->selectedCategory) {
            $this->subcategories = Category::where('parent_category_id', $this->selectedCategory)
                ->where('is_active', true)
                ->get();
        } else {
            $this->subcategories = collect();
        }
        
        $this->resetPage();
        $this->dispatch('close-mobile-filters');
    }

    public function render()
    {
        $query = Product::query()->where('status', true)->with(['firstVariantImage', 'category', 'brand', 'variantCombinations']);

        // Apply category/subcategory filters
        if (!empty($this->selectedSubcategories)) {
            // If subcategories are selected, filter by subcategories
            $query->whereIn('category_id', $this->selectedSubcategories);
        } elseif ($this->selectedCategory) {
            // If only parent category is selected, include both parent and its subcategories
            $categoryIds = [$this->selectedCategory];
            $subcategoryIds = Category::where('parent_category_id', $this->selectedCategory)
                ->where('is_active', true)
                ->pluck('id')
                ->toArray();
            $categoryIds = array_merge($categoryIds, $subcategoryIds);
            $query->whereIn('category_id', $categoryIds);
        }

        // Apply brand filter
        if (!empty($this->selectedBrands)) {
            $query->whereIn('brand_id', $this->selectedBrands);
        }

        // Filter by color and size using ProductVariantCombination
        // Note: Using LIKE patterns because variant_values is stored as longtext, not JSON column
        if (!empty($this->selectedColors)) {
            $query->whereHas('variantCombinations', function($q) {
                $q->where(function($subQ) {
                    foreach ($this->selectedColors as $color) {
                        $subQ->orWhere('variant_values', 'LIKE', "%{$color}%");
                    }
                });
            });
        }
        
        if (!empty($this->selectedSizes)) {
            $query->whereHas('variantCombinations', function($q) {
                $q->where(function($subQ) {
                    foreach ($this->selectedSizes as $size) {
                        $subQ->orWhere('variant_values', 'LIKE', "%{$size}%");
                    }
                });
            });
        }

        // Apply price filter
        if ($this->minPrice > 0 || $this->maxPrice < 10000) {
            $query->where(function($q) {
                $q->whereBetween('price', [$this->minPrice, $this->maxPrice])
                  ->orWhereBetween('discount_price', [$this->minPrice, $this->maxPrice]);
            });
        }

        // Apply search filter
        if ($this->searchQuery) {
            $query->where(function($q) {
                $q->where('name', 'like', '%'.$this->searchQuery.'%')
                  ->orWhere('description', 'like', '%'.$this->searchQuery.'%')
                  ->orWhereHas('category', function($categoryQuery) {
                      $categoryQuery->where('title', 'like', '%'.$this->searchQuery.'%');
                  })
                  ->orWhereHas('brand', function($brandQuery) {
                      $brandQuery->where('name', 'like', '%'.$this->searchQuery.'%');
                  });
            });
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'price_asc':
                $query->orderByRaw('COALESCE(discount_price, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(discount_price, price) DESC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'popularity':
            default:
                $query->orderBy('featured', 'desc')
                      ->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12);

        // Load wishlist items for authenticated users
        $wishlistItems = [];
        if (Auth::check()) {
            $wishlistService = app(WishlistService::class);
            $wishlistItems = $wishlistService->getWishlistItems()->pluck('product_id')->toArray();
            $this->wishlistItems = $wishlistItems;
        }

        return view('livewire.public.all-product', [
            'products' => $products,
            'parentCategories' => $this->parentCategories,
            'brands' => $this->brands,
            'showAllCategories' => $this->showAllCategories,
            'showAllBrands' => $this->showAllBrands,
            'subcategories' => $this->subcategories,
            'availableColors' => $this->availableColors,
            'availableSizes' => $this->availableSizes,
            'wishlistItems' => $wishlistItems,
        ]);
    }
}
