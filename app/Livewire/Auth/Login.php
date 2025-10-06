<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

#[Layout('components.layouts.app')]

#[Title('Login')]
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

        // Check if email exists
        $user = User::where('email', $this->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['No account found with this email address.'],
            ]);
        }

        // Check if password is correct
        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            throw ValidationException::withMessages([
                'password' => ['The password you entered is incorrect.'],
            ]);
        }

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

    public function redirectToGoogle()
    {
        \Log::info('Redirecting to Google OAuth');
        return redirect()->to(Socialite::driver('google')->redirect()->getTargetUrl());
    }

    public function handleGoogleCallback()
    {
        try {
            \Log::info('Google Callback URL', ['url' => request()->fullUrl()]);
            $googleUser = Socialite::driver('google')->user();
            \Log::info('Google User Data', (array) $googleUser);
            $user = User::updateOrCreate(
                ['google_id' => $googleUser->getId()],
                [
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'avatar' => $googleUser->avatar ?? null,
                    'password' => Hash::make(uniqid()),
                    'email_verified_at' => now(),
                ]
            );
            Auth::login($user, true);
            return redirect('/');
        } catch (\Exception $e) {
            \Log::error('Google Auth Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Failed to login with Google: ' . $e->getMessage());
            return redirect()->route('login');
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}