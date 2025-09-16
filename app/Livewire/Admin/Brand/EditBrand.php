<?php

namespace App\Livewire\Admin\Brand;

use App\Models\Brand;
use App\Services\ImageKitService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class EditBrand extends Component
{
    use WithFileUploads;

    public $brand;
    public $name;
    public $description;
    public $is_active;
    public $logo;
    public $existing_logo;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'is_active' => 'boolean',
        'logo' => 'nullable|image|max:2048', // 2MB max
    ];

    public function mount($brandId)
    {
        $this->brand = Brand::findOrFail($brandId);
        $this->name = $this->brand->name;
        $this->description = $this->brand->description;
        $this->is_active = $this->brand->is_active;
        $this->existing_logo = $this->brand->logo;
    }

    public function updatedName()
    {
        $this->validate(['name' => 'required|string|max:255']);
    }

    public function updatedLogo()
    {
        $this->validate(['logo' => 'nullable|image|max:2048']);
    }

    public function updateBrand()
    {
        $this->validate();

        try {
            $logoUrl = $this->existing_logo;

            // Handle logo upload if new logo is provided
            if ($this->logo) {
                $imageKitService = new ImageKitService();
                
                // Upload new logo
                $fileName = 'brand_' . Str::slug($this->name) . '_' . time() . '.' . $this->logo->getClientOriginalExtension();
                $uploadResult = $imageKitService->upload(
                    $this->logo,
                    $fileName,
                    'brands/'
                );

                if ($uploadResult && isset($uploadResult['url'])) {
                    // Delete old logo if it exists and upload was successful
                    if ($this->existing_logo) {
                        $imageKitService->deleteFileByUrl($this->existing_logo);
                    }
                    $logoUrl = $uploadResult['url'];
                } else {
                    session()->flash('error', 'Failed to upload logo. Please try again.');
                    return;
                }
            }

            // Update brand
            $this->brand->update([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'description' => $this->description,
                'is_active' => $this->is_active,
                'logo' => $logoUrl,
            ]);

            session()->flash('message', 'Brand updated successfully!');
            
            // Redirect to manage brands page
            return redirect()->route('admin.brand.manage');

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating the brand: ' . $e->getMessage());
        }
    }

    public function removeLogo()
    {
        if ($this->existing_logo) {
            try {
                $imageKitService = new ImageKitService();
                $deleted = $imageKitService->deleteFileByUrl($this->existing_logo);
                
                if ($deleted) {
                    $this->brand->update(['logo' => null]);
                    $this->existing_logo = null;
                    session()->flash('message', 'Logo removed successfully!');
                } else {
                    session()->flash('error', 'Could not locate file for deletion, but logo reference removed.');
                    $this->brand->update(['logo' => null]);
                    $this->existing_logo = null;
                }
            } catch (\Exception $e) {
                session()->flash('error', 'Failed to remove logo: ' . $e->getMessage());
            }
        }
    }

    public function cancel()
    {
        return redirect()->route('admin.brand.manage');
    }

    public function render()
    {
        return view('livewire.admin.brand.edit-brand');
    }
}