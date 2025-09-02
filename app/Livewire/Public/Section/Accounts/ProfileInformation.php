<?php

namespace App\Livewire\Public\Section\Accounts;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileInformation extends Component
{
    public $information;
    public $editing = false;
    public $changingPassword = false;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        // Fetch the authenticated user's data
        $this->information = Auth::user();
    }

    public function updateProfile()
    {
        // Validate profile information
        $this->validate([
            'information.name' => 'required|string|max:255',
            'information.email' => 'required|email|max:255|unique:users,email,' . $this->information->id,
        ]);

        // Save profile changes
        $this->information->save();
        $this->editing = false;
        session()->flash('message', 'Profile updated successfully.');
    }

    public function changePassword()
    {
        // Validate password inputs
        $this->validate([
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('The current password is incorrect.');
                    }
                },
            ],
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Update the password
        $this->information->password = Hash::make($this->new_password);
        $this->information->save();

        // Reset form fields
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->changingPassword = false;

        // Flash success message
        session()->flash('message', 'Password changed successfully.');
    }

    public function cancelEdit()
    {
        $this->information = Auth::user(); // Reload original data
        $this->editing = false;
        $this->changingPassword = false;
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
    }

    public function render()
    {
        return view('livewire.public.section.accounts.profile-information');
    }
}