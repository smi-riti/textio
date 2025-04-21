<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Coupon;

#[Layout('components.layouts.admin')]

class ManageCoupon extends Component
{
    public $couponId;
    public $code;
    public $discount_type = 'percentage';
    public $discount_value;
    public $expiration_date;
    public $status = false;
    public $showModal = false;
    public $isEditMode = false;
    public $search = '';

    protected $rules = [
        'code' => 'required|string|max:255|unique:coupons,code',
        'discount_type' => 'required|in:percentage,fixed,freeShipping',
        'discount_value' => 'nullable|numeric|min:0|required_if:discount_type,percentage,fixed',
        'expiration_date' => 'nullable|date|after_or_equal:today',
        'status' => 'boolean',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function updated($propertyName)
    {
        // Real-time validation
        $rules = $this->rules;
        if ($this->isEditMode) {
            $rules['code'] = 'required|string|max:255|unique:coupons,code,' . $this->couponId;
        }
        $this->validateOnly($propertyName, $rules);
    }

    public function render()
    {
        $coupons = Coupon::when($this->search, function ($query) {
            $query->where('code', 'like', '%' . $this->search . '%');
        })->latest()->get();
        return view('livewire.admin.manage-coupon', compact('coupons'));
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        $this->couponId = $coupon->id;
        $this->code = $coupon->code;
        $this->discount_type = $coupon->discount_type;
        $this->discount_value = $coupon->discount_value;
        $this->expiration_date = $coupon->expiration_date ? $coupon->expiration_date->format('Y-m-d') : null;
        $this->status = $coupon->status;
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->isEditMode) {
            $rules['code'] = 'required|string|max:255|unique:coupons,code,' . $this->couponId;
        }
        $this->validate($rules);

        $data = [
            'code' => $this->code,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_type === 'freeShipping' ? null : $this->discount_value,
            'expiration_date' => $this->expiration_date,
            'status' => $this->status,
        ];

        if ($this->isEditMode) {
            Coupon::find($this->couponId)->update($data);
            session()->flash('message', 'Coupon updated successfully.');
        } else {
            Coupon::create($data);
            session()->flash('message', 'Coupon created successfully.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id)
    {
        Coupon::findOrFail($id)->delete();
        session()->flash('message', 'Coupon deleted successfully.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->couponId = null;
        $this->code = '';
        $this->discount_type = 'percentage';
        $this->discount_value = null;
        $this->expiration_date = null;
        $this->status = false;
        $this->resetValidation();
    }
}