<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white rounded-2xl  p-6 sm:p-8 lg:p-10 animate-fadeIn">
        <!-- Logo -->
        <div class="text-center mb-8">
            <img width="80" src="{{ asset('assets/textio.png') }}" alt="logo" class="mx-auto">
        </div>
        <!-- Form Header -->
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-semibold text-gray-900">Sign In</h1>
            <p class="text-gray-600 text-sm mt-2 opacity-90">Access your Textio account</p>
        </div>
        <!-- Form -->
        <form wire:submit.prevent="login" class="space-y-5">
            <div>
                <input 
                    type="email" 
                    wire:model="email" 
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8f4da7] transition-colors duration-200 placeholder:text-gray-400 text-gray-900 text-sm" 
                    placeholder="Your email address"
                    autofocus 
                    required
                >
                @error('email') 
                    <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> 
                @enderror
            </div>
            <div>
                <input 
                    type="password" 
                    wire:model="password" 
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-[#8f4da7] transition-colors duration-200 placeholder:text-gray-400 text-gray-900 text-sm" 
                    placeholder="Your password" 
                    required
                >
                @error('password') 
                    <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> 
                @enderror
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        wire:model="remember" 
                        id="remember" 
                        class="h-4 w-4 text-[#8f4da7] focus:ring-0 focus:ring-offset-0 border-gray-200 rounded"
                    >
                    <label for="remember" class="ml-2 text-sm text-gray-900 opacity-90">Remember me</label>
                </div>
            </div>
            <button 
                type="submit" 
                class="w-full px-4 py-3 bg-[#8f4da7] text-white rounded-lg hover:bg-[#7a3f8f] transition-colors duration-200 text-sm font-medium hover-scale"
            >
                Sign In
            </button>
        </form>
        <!-- Divider -->
        <div class="flex items-center my-6">
            <div class="flex-grow h-px bg-gray-200"></div>
            <span class="mx-4 text-sm text-gray-600 opacity-70">or</span>
            <div class="flex-grow h-px bg-gray-200"></div>
        </div>
        <!-- Social Login -->
        <div>
            <a href="{{ route('google.redirect') }}" class="flex items-center justify-center px-4 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors duration-200 text-sm font-medium hover-scale">
                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 
                        10 10 10 10-4.48 10-10S17.52 2 
                        12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 
                        8-8 8 3.59 8 8-3.59 8-8 
                        8zm4.59-12.42L15.17 12l1.42 4.58c-.63.63-1.71 
                        1.03-2.83 1.03-1.12 0-2.2-.4-2.83-1.03L9.5 
                        12l1.42-4.58c.63-.63 1.71-1.03 2.83-1.03s2.2.4 
                        2.83 1.03z" fill="currentColor"/>
                </svg>
                Sign in with Google
            </a>
        </div>
        <!-- Sign Up Link -->
        <p class="text-center mt-6 text-sm text-gray-600 opacity-90">
            Don't have an account? 
            <a href="{{ route('register') }}" class="text-[#8f4da7] hover:underline font-medium">Sign up</a>
        </p>
    </div>
</div>
