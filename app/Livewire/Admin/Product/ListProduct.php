<?php

namespace App\Livewire\Admin\Product;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Services\ImageKitService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

use Livewire\Attributes\Layout;
#[Layout('components.layouts.admin')]

class ListProduct extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $brandFilter = '';
    public $statusFilter = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'brandFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public $productIdToDelete = null;

    protected $listeners = ['deleteProduct'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingBrandFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function confirmDelete($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $this->productIdToDelete = $productId;
            $this->dispatch('openConfirmation', 
                'Delete Product', 
                "Are you sure you want to delete '{$product->name}'? This action cannot be undone.",
                'Delete',
                'deleteProduct'
            );
        }
    }

    public function deleteProduct()
    {
        if ($this->productIdToDelete) {
            $product = Product::find($this->productIdToDelete);
            if ($product) {
                $productName = $product->name;
                
                // Delete associated images from ImageKit
                $imageKitService = new ImageKitService();
                foreach ($product->images as $image) {
                    if ($image->image_file_id) {
                        try {
                            $imageKitService->delete($image->image_file_id);
                        } catch (\Exception $e) {
                            \Log::error('Failed to delete image from ImageKit: ' . $e->getMessage());
                        }
                    }
                    
                    // Also delete from local storage if path exists (backward compatibility)
                    if (Storage::disk('public')->exists($image->image_path)) {
                        Storage::disk('public')->delete($image->image_path);
                    }
                }
                
                // Delete the product (this will cascade delete images and highlights)
                $product->delete();
                
                session()->flash('success', "Product '{$productName}' has been deleted successfully!");
                $this->productIdToDelete = null;
            }
        }
    }

    public function toggleStatus($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $product->update(['status' => !$product->status]);
            $this->dispatch('status-updated', name: $product->name, status: $product->status);
        }
    }

    public function toggleFeatured($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $product->update(['featured' => !$product->featured]);
            $this->dispatch('featured-updated', name: $product->name, featured: $product->featured);
        }
    }

    public function render()
    {
        $query = Product::query()
            ->with(['category:id,title', 'brand:id,name'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('sku', 'like', '%' . $this->search . '%');
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->when($this->brandFilter, function ($query) {
                $query->where('brand_id', $this->brandFilter);
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $products = $query->paginate($this->perPage);
        $categories = Category::where('is_active', true)->select('id', 'title')->get();
        $brands = Brand::where('is_active', true)->select('id', 'name')->get();

        return view('livewire.admin.product.list-product', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }
}
