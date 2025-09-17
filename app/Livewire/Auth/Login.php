<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

#[Layout('components.layouts.app')]
class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;
    

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function mount()
    {
        if (Cookie::has('remembered_email')) {
            $this->email = Cookie::get('remembered_email');
            $this->remember = true;
        }
    }

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            if ($this->remember) {
                Cookie::queue(
                    'remembered_email',
                    $this->email,
                    60 * 24 * 30
                );
            } else {
                Cookie::queue(Cookie::forget('remembered_email'));
            }
            return redirect()->intended(Auth::user()->isAdmin ? 'admin' : '/');
        }

        session()->flash('error', 'Invalid credentials. Please try again.');
    }

    public function redirectToGoogle()
    {
        return redirect()->to(Socialite::driver('google')->redirect()->getTargetUrl());
    }

   public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->user();
        $user = User::updateOrCreate(
            ['google_id' => $googleUser->getId()],
            [
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'avatar' => $googleUser->avatar ?? null,
                'password' => Hash::make(uniqid()), // Use uniqid for unique password
                'email_verified_at' => now(),
            ]
        );

        Auth::login($user, true); // Ensure remember is set
        return redirect('/dashboard'); // Hardcode redirect for testing
    } catch (\Exception $e) {
        \Log::error('Google Auth Error: ' . $e->getMessage());
        session()->flash('error', 'Failed to login with Google.');
        return redirect()->route('login');
    }
}

    public function render()
    {
        return view('livewire.auth.login');
    }
}