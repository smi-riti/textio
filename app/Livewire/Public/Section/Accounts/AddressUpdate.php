<?php

namespace App\Livewire\Public\Section\Accounts;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AddressUpdate extends Component
{
    #[Validate('required|min:3')]
    public $name;

    #[Validate('required|digits:10')]
    public $phone;

    #[Validate('required|digits:6')]
    public $postal_code;

    #[Validate('required|min:5')]
    public $address_line;

    #[Validate('nullable|min:3')]
    public $area;

    #[Validate('required|min:3')]
    public $city = '';

    #[Validate('required|min:3')]
    public $state = '';

    #[Validate('nullable|min:3')]
    public $landmark;

    #[Validate('nullable|digits:10')]
    public $alt_phone;

    #[Validate('required|in:home,office')]
    public $address_type;

    public $address_id;

    public function mount($addressId = null)
    {
        if ($addressId) {
            $this->edit($addressId);
        } else {
            $this->resetForm();
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'user_id' => Auth::user()->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'postal_code' => $this->postal_code,
            'address_line' => $this->address_line,
            'area' => $this->area,
            'city' => $this->city,
            'state' => $this->state,
            'landmark' => $this->landmark,
            'alt_phone' => $this->alt_phone,
            'address_type' => $this->address_type,
        ];

        if ($this->address_id) {
            Address::where('id', $this->address_id)->where('user_id', Auth::id())->update($data);
            session()->flash('message', 'Address updated successfully.');
        } else {
            Address::create($data);
            session()->flash('message', 'Address added successfully.');
        }

        $this->resetForm();
        $this->dispatch('close-modal');
        $this->dispatch('address-updated');
    }

    public function edit($addressId)
    {
        $address = Address::where('id', $addressId)->where('user_id', Auth::id())->firstOrFail();
        $this->address_id = $address->id;
        $this->name = $address->name;
        $this->phone = $address->phone;
        $this->postal_code = $address->postal_code;
        $this->address_line = $address->address_line;
        $this->area = $address->area;
        $this->city = $address->city;
        $this->state = $address->state;
        $this->landmark = $address->landmark;
        $this->alt_phone = $address->alt_phone;
        $this->address_type = $address->address_type;
    }

    public function resetForm()
    {
        $this->reset([
            'name',
            'phone',
            'postal_code',
            'address_line',
            'area',
            'city',
            'state',
            'landmark',
            'alt_phone',
            'address_type',
            'address_id'
        ]);
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.public.section.accounts.address-update');
    }
}