<?php

namespace App\Livewire\Admin\Brand;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageKitService;
use App\Models\Brand;

#[Layout('components.layouts.admin')]
class CreateBrand extends Component
{
     use WithFileUploads;

    public $name = '';
    public $slug = '';
    public $logo;
    public $description = '';
    public $is_active = true;
    public $meta_title = '';
    public $meta_description = '';
    public $editingBrandId = null;
    public $imagePreview = null;

    public $fileId = null;
    public $imageUrl = null;
    public $imagePath = null;

    protected $listeners = ['editBrand' => 'loadBrand'];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:120|unique:brands,slug,' . ($this->editingBrandId ?: 'NULL') . ',id',
            'logo' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        if ($propertyName === 'name' && !$this->slug) {
            $this->slug = Str::slug($this->name);
        }
    }

  public function removeImage()
    {
        if ($this->fileId && $this->editingBrandId) {
            try {
                $imageKitService = new ImageKitService();
                $imageKitService->delete($this->fileId);
            } catch (\Exception $e) {
                session()->flash('error', 'Error deleting image: ' . $e->getMessage());
            }
        }
        
        // Reset image properties
        $this->reset('logo', 'fileId', 'imageUrl', 'imagePath');
        $this->imagePreview = null;
    }

public function saveBrand()
{
    $this->validate();

    try {
        $logoUrl = $this->imageUrl;

        // If there's a new image, upload it to ImageKit
        if ($this->logo) {
            $imageKitService = new ImageKitService();
            
            \Log::debug('Attempting to upload logo', [
                'logo_type' => get_class($this->logo),
                'logo_size' => $this->logo->getSize(),
                'logo_extension' => $this->logo->getClientOriginalExtension()
            ]);

            try {
                $uploadResult = $imageKitService->upload(
                    $this->logo, 
                    'brand_' . Str::slug($this->name) . '_' . time() . '.' . $this->logo->getClientOriginalExtension(),
                    config('services.imagekit.folders.brand', 'brands') // Default to 'brands' if not set
                );
                
                if ($uploadResult && isset($uploadResult->url)) {
                    $logoUrl = $uploadResult->url;
                    \Log::debug('Image uploaded successfully', ['url' => $logoUrl]);
                } else {
                    throw new \Exception('Image upload failed: No URL returned');
                }
            } catch (\Exception $e) {
                \Log::error('ImageKit upload failed: ' . $e->getMessage());
                // Continue without image rather than failing completely
                $logoUrl = null;
                session()->flash('warning', 'Logo upload failed: ' . $e->getMessage() . '. Brand was saved without logo.');
            }
        }

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'logo' => $logoUrl,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
        ];

        \Log::debug('Saving brand data:', $data);

        if ($this->editingBrandId) {
            $brand = Brand::find($this->editingBrandId);
            $brand->update($data);
            session()->flash('message', 'Brand updated successfully.');
        } else {
            $brand = Brand::create($data);
            session()->flash('message', 'Brand created successfully.');
        }

          return redirect()->route('admin.brand.manage');
        
    } catch (\Exception $e) {
        \Log::error('Error saving brand: ' . $e->getMessage());
        session()->flash('error', 'Error: ' . $e->getMessage());
    }
}

    public function loadBrand($id)
    {
         $brand = Brand::findOrFail($id);
        $this->editingBrandId = $id;
        $this->name = $brand->name;
        $this->slug = $brand->slug;
        $this->description = $brand->description;
        $this->is_active = $brand->is_active;
        $this->meta_title = $brand->meta_title;
        $this->meta_description = $brand->meta_description;
        $this->imagePreview = $brand->logo;
        $this->imageUrl = $brand->logo;
        $this->logo = null;
    }

    public function resetForm()
    {
       $this->reset([
            'name', 'slug', 'logo', 'description',
            'is_active', 'meta_title', 'meta_description',
            'editingBrandId', 'imagePreview', 'fileId', 
            'imageUrl', 'imagePath'
        ]);
    }

 public function render()
    {
        return view('livewire.admin.brand.create-brand');
    }
}

