
@extends('auth.layout')
@section('content')


<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl w-full bg-white shadow-lg rounded-lg flex flex-col lg:flex-row overflow-hidden">
        <!-- Left Side (Form) -->
        <div class="w-full lg:w-1/2 p-8 flex flex-col justify-center">
            <div class="block lg:hidden text-center mb-6">
                <img class="mx-auto" width="120" src="../../assets/images/logo.svg" alt="logo">
            </div>
            <div class="mb-8 text-center lg:text-left">
                <h1 class="text-3xl font-bold text-gray-900">Sign In</h1>
                <p class="text-gray-500 mt-2">Sign in to Textio to continue</p>
            </div>
            <form class="space-y-6 mb-6">
                <div>
                    <input type="email" class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-gray-900" placeholder="Enter email" autofocus required>
                </div>
                <div>
                    <input type="password" class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-gray-900" placeholder="Enter password" required>
                </div>
                <div class="flex flex-col gap-2 text-center lg:text-left">
                    <p class="text-sm text-gray-500">Can't access your account? <a href="#" class="text-purple-600 hover:underline">Reset your password now</a>.</p>
                    <button class="w-full lg:w-auto mt-2 px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-md shadow focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition">Sign In</button>
                </div>
            </form>
            <div class="flex flex-col items-center gap-2">
                {{-- <a href="#" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded hover:bg-gray-50 transition">
                    <i class="ti-google text-red-500"></i> <span>Sign in with Google</span>
                </a> --}}
                {{-- <a href="#" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded hover:bg-gray-50 transition">
                    <i class="ti-facebook text-blue-600"></i> <span>Sign in with Facebook</span>
                </a> --}}
            </div>
            <p class="text-center block lg:hidden mt-8">
                Don't have an account? <a href="#" class="text-purple-600 hover:underline">Sign up</a>.
            </p>
        </div>
        <!-- Right Side (Info) -->
        <div class="hidden lg:flex w-1/2 flex-col items-center justify-between border-l border-gray-200 bg-gray-50 py-8 px-8 text-center">
            <div class="mb-8">
                <img width="120" src="{{ asset('assets/textio.png') }}" alt="logo" class="mx-auto">
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900">Welcome to Textio!</h3>
                <p class="text-gray-600 my-6">If you don't have an account, would you like to register right now?</p>
                <a href="{{route('register')}}" class="inline-block px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-md shadow focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition">Sign Up</a>
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
 