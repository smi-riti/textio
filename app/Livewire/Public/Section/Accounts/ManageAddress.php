<?php

namespace App\Livewire\Public\Section\Accounts;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class ManageAddress extends Component
{
    public $addresses = [];

    public function mount()
    {
        if (Auth::check()) {
            $this->addresses = Address::where('user_id', Auth::id())->latest()->get()->toArray();
        }
    }

    #[On('address-updated')]
    public function refreshAddresses()
    {
        $this->addresses = Address::where('user_id', Auth::id())->latest()->get()->toArray();
    }

    public function delete($addressId)
    {
        $address = Address::where('id', $addressId)->where('user_id', Auth::id())->firstOrFail();
        $address->delete();
        $this->addresses = array_filter($this->addresses, fn($add) => $add['id'] !== $addressId);
        session()->flash('message', 'Address deleted successfully.');
    }

    public function render()
    {
        return view('livewire.public.section.accounts.manage-address');
    }
}