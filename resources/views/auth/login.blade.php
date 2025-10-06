@extends('auth.layout')
@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl w-full bg-white rounded-lg flex flex-col lg:flex-row overflow-hidden">
        <!-- Left Side (Form) -->
        <div class="w-full lg:w-1/2 p-8 flex flex-col justify-center">
            <div class="block lg:hidden text-center mb-6">
                <img class="mx-auto" width="120" src="../../assets/images/logo.svg" alt="logo">
            </div>
            <div class="mb-8 text-center lg:text-left">
                <h1 class="text-3xl text-[#171717]">Sign In</h1>
                <p class="text-gray-500 mt-2">Sign in to Textio to continue</p>
            </div>
            
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            <form class="space-y-6 mb-6" wire:submit.prevent="login">
                <div>
                    <input 
                        type="email" 
                        wire:model="email"
                        class="rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-400 focus:outline-none focus:border-[#8f4da7] text-[#171717]" 
                        placeholder="Enter email" 
                        autofocus 
                        required>
                    @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <input 
                        type="password" 
                        wire:model="password"
                        class="rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-400 focus:outline-none focus:border-[#8f4da7] text-[#171717]" 
                        placeholder="Enter password" 
                        required>
                    @error('password')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        wire:model="remember"
                        id="remember" 
                        class="h-4 w-4 text-[#8f4da7] focus:ring-[#8f4da7] border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-[#171717]">
                        Remember me
                    </label>
                </div>
                <div class="flex flex-col gap-2 text-center lg:text-left">
                    <p class="text-sm text-gray-500">Can't access your account? <a href="#" class="text-[#8f4da7] hover:underline">Reset your password now</a>.</p>
                    <button type="submit" class="w-full lg:w-auto mt-2 px-6 py-2 bg-[#8f4da7] hover:bg-[#7a3d8f] text-white rounded-md focus:outline-none transition">Sign In</button>
                </div>
            </form>
            <div class="flex flex-col items-center gap-2">
                <a href="{{ route('google.redirect') }}" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded hover:bg-gray-50 transition">
                    <i class="ti-google text-red-500"></i> <span>Sign in with Google</span>
                </a>
            </div>
            <p class="text-center block lg:hidden mt-8">
                Don't have an account? <a href="{{route('register')}}" class="text-[#8f4da7] hover:underline">Sign up</a>.
            </p>
        </div>
        <!-- Right Side (Info) -->
        <div class="hidden lg:flex w-1/2 flex-col items-center justify-between border-l border-gray-200 bg-gray-50 py-8 px-8 text-center">
            <div class="mb-8">
                <img width="120" src="{{ asset('assets/textio.png') }}" alt="logo" class="mx-auto">
            </div>
            <div>
                <h3 class="text-2xl text-[#171717]">Welcome to Textio!</h3>
                <p class="text-gray-600 my-6">If you don't have an account, would you like to register right now?</p>
                <a href="{{route('register')}}" class="inline-block px-6 py-2 bg-[#8f4da7] hover:bg-[#7a3d8f] text-white rounded-md focus:outline-none transition">Sign Up</a>
            </div>
            <ul class="flex justify-center gap-4 mt-8 text-sm text-gray-500">
                <li>
                    <a href="#" class="hover:underline">Privacy Policy</a>
                </li>
                <li>
                    <a href="#" class="hover:underline">Terms & Conditions</a>
                </li>
            </ul>
        </div>
    </div>
</div>

@endsection