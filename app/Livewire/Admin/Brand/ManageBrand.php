<?php

namespace App\Livewire\Admin\Brand;

use App\Models\Brand;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ManageBrand extends Component
{
    use WithFileUploads, WithPagination;

    public $name = '';
    public $slug = '';
    public $logo;
    public $description = '';
    public $is_active = true;
    public $meta_title = '';
    public $meta_description = '';
    public $editingBrandId = null;
    public $showDeleted = false;

    #[Layout('components.layouts.admin')]
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

        if ($propertyName === 'name') {
            $this->slug = Str::slug($this->name);
        }
    }

    public function saveBrand()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
        ];

        if ($this->logo && $this->logo instanceof \Illuminate\Http\UploadedFile) {
            $data['logo'] = $this->logo->store('brands', 'public');
        }

        if ($this->editingBrandId) {
            Brand::find($this->editingBrandId)->update($data);
            session()->flash('message', 'Brand updated successfully.');
        } else {
            Brand::create($data);
            session()->flash('message', 'Brand created successfully.');
        }

        $this->resetForm();
    }

    public function editBrand($id)
    {
        $brand = Brand::findOrFail($id);
        $this->editingBrandId = $id;
        $this->name = $brand->name;
        $this->slug = $brand->slug;
        $this->description = $brand->description;
        $this->is_active = $brand->is_active;
        $this->meta_title = $brand->meta_title;
        $this->meta_description = $brand->meta_description;
        $this->logo = null; // reset upload
    }

    public function deleteBrand($id)
    {
        Brand::findOrFail($id)->delete();
        session()->flash('message', 'Brand deleted successfully.');
    }

    public function restoreBrand($id)
    {
        Brand::withTrashed()->findOrFail($id)->restore();
        session()->flash('message', 'Brand restored successfully.');
    }

    public function resetForm()
    {
        $this->reset([
            'name', 'slug', 'logo', 'description',
            'is_active', 'meta_title', 'meta_description',
            'editingBrandId'
        ]);
    }

     public function showTrash()
    {
        $this->showDeleted = true;
        $this->resetPage();
    }
    
 public function showList()
    {
        $this->showDeleted = false;
        $this->resetPage();
    }

    public function render()
    {
        $brands = $this->showDeleted
            ? Brand::onlyTrashed()->paginate(10)
            : Brand::paginate(10);

        return view('livewire.admin.brand.manage-brand', [
            'brands' => $brands,
        ]);
    }
}
