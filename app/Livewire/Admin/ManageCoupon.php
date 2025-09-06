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
    public $start_date;
    public $status = false;
    public $showModal = false;
    public $isEditMode = false;
    public $minimum_purchase_amount;
    public $search = '';



    protected $rules = [
        'code' => 'required|string|max:255|unique:coupons,code',
        'discount_type' => 'required|in:percentage,fixed,freeShipping',
        'discount_value' => 'nullable|numeric|min:0|required_if:discount_type,percentage,fixed',
        'start_date' => 'nullable|date|after_or_equal:today',
        'minimum_purchase_amount'=>'required|numeric|min:0',
        'expiration_date' => 'nullable|date|after_or_equal:start_date',
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
        $this->minimum_purchase_amount=$coupon->minimum_purchase_amount;
        $this->discount_value = $coupon->discount_value;
        $this->start_date = $coupon->start_date?->format('Y-m-d');
        $this->expiration_date = $coupon->expiration_date?->format('Y-m-d');
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
            'minimum_purchase_amount' => $this->minimum_purchase_amount,
            'discount_value' => $this->discount_type === 'freeShipping' ? null : $this->discount_value,
            'start_date' => $this->start_date ? \Carbon\Carbon::parse($this->start_date) : null,
            'expiration_date' => $this->expiration_date ? \Carbon\Carbon::parse($this->expiration_date) : null,
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
        $this->start_date = null;
        $this->minimum_purchase_amount = null;
        $this->expiration_date = null;
        $this->status = false;
        $this->resetValidation();
    }
}