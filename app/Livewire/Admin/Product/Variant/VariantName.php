<?php

namespace App\Livewire\Admin\Product\Variant;

use App\Models\ProductVariant;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

class VariantName extends Component
{
    #[Validate('required|min:3')]
    public $variant_name = '';
    public $showModal = false;
    public $variants = [];

    public function mount()
    {
        $this->variants = ProductVariant::all();
    }

    public function OpenModal()
    {
        $this->showModal = true;
    }

    public function remove($id)
    {
        $variant = ProductVariant::find($id);
        if ($variant) {
            $variant->delete();
            $this->variants = ProductVariant::all(); // Refresh variants
            session()->flash('message', 'Variant deleted successfully.');
        } else {
            session()->flash('error', 'Variant not found.');
        }
    }

    public function CloseModal()
    {
        $this->showModal = false;
    }

    public function save()
    {
        $this->validate();
        ProductVariant::create([
            'variant_name' => $this->variant_name,
        ]);
        $this->variants = ProductVariant::all(); // Refresh variants
        $this->reset(['variant_name', 'showModal']);
        session()->flash('message', 'Variant created successfully!');
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.product.variant.variant-name');
    }
}