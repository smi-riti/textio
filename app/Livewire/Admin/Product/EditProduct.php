<?php

namespace App\Livewire\Admin\Product;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class EditProduct extends Component
{
    use WithFileUploads;

    public Product $product;
    public $categories;
    public $brands;
    
    // Fields for editing
    public $editingField = null;
    public $name;
    public $description;
    public $price;
    public $discount_price;
    public $category_id;
    public $brand_id;
    public $status;
    public $is_customizable;
    public $print_area;
    public $meta_title;
    public $meta_description;
    public $featured;

    // Temporary values while editing
    public $tempValue = '';
    public $tempArray = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'nullable|numeric|min:0',
        'discount_price' => 'nullable|numeric|min:0',
        'category_id' => 'nullable|exists:categories,id',
        'brand_id' => 'nullable|exists:brands,id',
        'status' => 'boolean',
        'is_customizable' => 'boolean',
        'print_area' => 'nullable|array',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string',
        'featured' => 'boolean',
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->categories = Category::where('is_active', true)->orderBy('title')->get();
        $this->brands = Brand::where('is_active', true)->orderBy('name')->get();
        $this->initializeFields();
    }

    public function initializeFields()
    {
        $this->name = $this->product->name;
        $this->description = $this->product->description;
        $this->price = $this->product->price;
        $this->discount_price = $this->product->discount_price;
        $this->category_id = $this->product->category_id;
        $this->brand_id = $this->product->brand_id;
        $this->status = $this->product->status;
        $this->is_customizable = $this->product->is_customizable;
        $this->print_area = $this->product->print_area ?? [];
        $this->meta_title = $this->product->meta_title;
        $this->meta_description = $this->product->meta_description;
        $this->featured = $this->product->featured;
    }

    public function startEdit($field)
    {
        $this->editingField = $field;
        
        // Set temporary value based on field type
        switch ($field) {
            case 'print_area':
                $this->tempArray = $this->product->print_area ?? [];
                break;
            case 'status':
            case 'is_customizable':
            case 'featured':
                $this->tempValue = $this->product->{$field} ? '1' : '0';
                break;
            case 'category_id':
            case 'brand_id':
                $this->tempValue = $this->product->{$field} ?? '';
                break;
            default:
                $this->tempValue = $this->product->{$field} ?? '';
                break;
        }
    }

    public function cancelEdit()
    {
        $this->editingField = null;
        $this->tempValue = '';
        $this->tempArray = [];
    }

    public function saveField($field)
    {
        // Validate specific field
        $this->validateField($field);

        try {
            $value = $this->prepareValueForSaving($field);
            
            // Special handling for name field (auto-generate slug)
            if ($field === 'name') {
                $this->product->update([
                    'name' => $value,
                    'slug' => Str::slug($value)
                ]);
            } else {
                $this->product->update([$field => $value]);
            }

            // Refresh the product model
            $this->product = $this->product->fresh();

            // Update component property
            $this->{$field} = $value;

            $this->editingField = null;
            $this->tempValue = '';
            $this->tempArray = [];

            session()->flash('message', ucfirst(str_replace('_', ' ', $field)) . ' updated successfully!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update ' . str_replace('_', ' ', $field) . ': ' . $e->getMessage());
        }
    }

    private function validateField($field)
    {
        $rules = [];
        
        switch ($field) {
            case 'name':
                $rules['tempValue'] = 'required|string|max:255';
                break;
            case 'price':
            case 'discount_price':
                $rules['tempValue'] = 'nullable|numeric|min:0';
                break;
            case 'category_id':
                $rules['tempValue'] = 'nullable|exists:categories,id';
                break;
            case 'brand_id':
                $rules['tempValue'] = 'nullable|exists:brands,id';
                break;
            case 'meta_title':
                $rules['tempValue'] = 'nullable|string|max:255';
                break;
            case 'description':
            case 'meta_description':
                $rules['tempValue'] = 'nullable|string';
                break;
            case 'print_area':
                $rules['tempArray'] = 'nullable|array';
                break;
        }

        if (!empty($rules)) {
            $this->validate($rules);
        }
    }

    private function prepareValueForSaving($field)
    {
        switch ($field) {
            case 'print_area':
                return $this->tempArray;
            case 'status':
            case 'is_customizable':
            case 'featured':
                return (bool) $this->tempValue;
            case 'price':
            case 'discount_price':
                return $this->tempValue ? (float) $this->tempValue : null;
            case 'category_id':
            case 'brand_id':
                return $this->tempValue ? (int) $this->tempValue : null;
            default:
                return $this->tempValue;
        }
    }

    public function addPrintArea()
    {
        $this->tempArray[] = '';
    }

    public function removePrintArea($index)
    {
        unset($this->tempArray[$index]);
        $this->tempArray = array_values($this->tempArray);
    }

    public function render()
    {
        return view('livewire.admin.product.edit-product');
    }
}