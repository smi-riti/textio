<?php

namespace App\Livewire\Admin\Product\Variant;

use App\Models\ProductVariant;
use App\Models\ProductVariantValue;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
class VariantValus extends Component
{
    #[Validate('required')]
    public $product_variant_id;

    public $values = [''];

    public $showModal = false;
    public $variantValues = [];
    public $variants = [];

    public function rules()
    {
        return [
            'product_variant_id' => 'required',
            'values' => 'required|array|min:1',
            'values.*' => 'required|string|min:1',
        ];
    }

    public function messages()
    {
        return [
            'values.*.required' => 'The variant value field is required.',
            'values.*.string' => 'The variant value must be a string.',
            'values.*.min' => 'The variant value must be at least 2 characters.',
        ];
    }

    public function mount()
    {
        $this->variantValues = ProductVariantValue::with('productVariant')->get();
        $this->variants = ProductVariant::select('id', 'variant_name')->get();
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['product_variant_id', 'values']);
        $this->values = [''];
    }

   public function deleteValue($id)
{
    $value = ProductVariantValue::find($id);
    if ($value) {
        $value->delete();
    }
    $this->variantValues = ProductVariantValue::with('productVariant')->get();
}

    public function addValue()
    {
        $this->values[] = '';
    }

    public function removeValue($index)
    {
        if (count($this->values) > 1) {
            unset($this->values[$index]);
            $this->values = array_values($this->values);
        }
    }

    public function save()
    {
        $this->validate();

        foreach ($this->values as $value) {
            if (!empty(trim($value))) {
                ProductVariantValue::create([
                    'value' => trim($value),
                    'product_variant_id' => $this->product_variant_id
                ]);
            }
        }

        $this->reset(['product_variant_id', 'values']);
        $this->values = [''];
        $this->showModal = false;
        $this->variantValues = ProductVariantValue::all();
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.product.variant.variant-valus');
    }
}