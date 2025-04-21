<?php

namespace App\Livewire\Auth;

use App\Mail\UserRegisterMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $contact = '';
    public $password = '';
  

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users',      
        'password' => 'required|min:8|confirmed',
    ];

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,          
            'password' => Hash::make($this->password),
        ]);
       
        return redirect()->route('public.home');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
