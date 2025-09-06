<?php

namespace App\Livewire\Public\Section\Coupon;

use App\Models\Coupon;
use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ApplyCoupon extends Component
{
    // CHANGED: Updated max length to 50 to match database schema
    #[Validate('required|string|max:50')]
    public $couponCode = '';
    public $couponError = '';
    public $cartTotal;
    // NEW: Added properties for coupons list and applied coupon
    public $coupons = [];
    public $appliedCoupon = null;

    public function mount($cartTotal)
    {
        $this->cartTotal = $cartTotal;
        // NEW: Load valid coupons on mount
        $this->loadCoupons();
        // NEW: Check if a coupon is already applied in the session
        $this->appliedCoupon = session('applied_coupon', null);
    }

    // NEW: Method to load valid coupons
    public function loadCoupons()
    {
        $this->coupons = Coupon::where('status', true)
            ->where(function ($query) {
                $query->whereNull('start_date')
                      ->orWhere('start_date', '<=', Carbon::now());
            })
            ->where(function ($query) {
                $query->whereNull('expiration_date')
                      ->orWhere('expiration_date', '>=', Carbon::now());
            })
            ->get();
    }

    public function applyCoupon($code = null)
    {
        // NEW: Allow applying coupon from input or directly from coupon list
        $this->couponCode = $code ?? $this->couponCode;
        $this->validate();

        $coupon = Coupon::where('code', $this->couponCode)->first();

        if (!$coupon) {
            $this->couponError = 'Invalid coupon code.';
            $this->appliedCoupon = null;
            session()->forget('applied_coupon');
            $this->dispatch('coupon-applied', couponCode: null, discount: 0, freeShipping: false);
            return;
        }

        if (!$coupon->status) {
            $this->couponError = 'This coupon is inactive.';
            $this->appliedCoupon = null;
            session()->forget('applied_coupon');
            $this->dispatch('coupon-applied', couponCode: null, discount: 0, freeShipping: false);
            return;
        }

        // CHANGED: Use now() instead of today() for precise date/time checks
        if ($coupon->start_date && Carbon::now()->lt($coupon->start_date)) {
            $this->couponError = 'This coupon is not yet valid.';
            $this->appliedCoupon = null;
            session()->forget('applied_coupon');
            $this->dispatch('coupon-applied', couponCode: null, discount: 0, freeShipping: false);
            return;
        }

        if ($coupon->expiration_date && Carbon::now()->gt($coupon->expiration_date)) {
            $this->couponError = 'This coupon has expired.';
            $this->appliedCoupon = null;
            session()->forget('applied_coupon');
            $this->dispatch('coupon-applied', couponCode: null, discount: 0, freeShipping: false);
            return;
        }

        // NEW: Check minimum_purchase_amount
        if ($coupon->minimum_purchase_amount && $this->cartTotal < $coupon->minimum_purchase_amount) {
            $this->couponError = 'Cart total must be at least ₹' . number_format($coupon->minimum_purchase_amount, 2) . ' to use this coupon.';
            $this->appliedCoupon = null;
            session()->forget('applied_coupon');
            $this->dispatch('coupon-applied', couponCode: null, discount: 0, freeShipping: false);
            return;
        }

        // NEW: Prevent applying a new coupon if one is already applied
        if ($this->appliedCoupon && $this->appliedCoupon !== $this->couponCode) {
            $this->couponError = 'Only one coupon can be applied at a time.';
            return;
        }

        $this->couponError = '';
        $discount = 0;
        $freeShipping = false;

        if ($coupon->discount_type === 'percentage') {
            $discount = ($this->cartTotal * $coupon->discount_value) / 100;
        } elseif ($coupon->discount_type === 'fixed') {
            $discount = min($coupon->discount_value, $this->cartTotal);
        } elseif ($coupon->discount_type === 'freeShipping') {
            $discount = 0;
            $freeShipping = true;
        }

        // NEW: Store applied coupon in session
        $this->appliedCoupon = $this->couponCode;
        session(['applied_coupon' => $this->couponCode]);

        session()->flash('success', 'Coupon applied successfully. Discount: ₹' . number_format($discount, 2));
        // CHANGED: Added freeShipping flag to dispatched event
        $this->dispatch('coupon-applied', couponCode: $this->couponCode, discount: $discount, freeShipping: $freeShipping);
    }

    // NEW: Method to remove applied coupon
    public function removeCoupon()
    {
        $this->appliedCoupon = null;
        $this->couponCode = '';
        $this->couponError = '';
        session()->forget('applied_coupon');
        session()->flash('success', 'Coupon removed successfully.');
        $this->dispatch('coupon-applied', couponCode: null, discount: 0, freeShipping: false);
    }

    public function render()
    {
        return view('livewire.public.section.coupon.apply-coupon');
    }
}