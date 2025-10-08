<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $metaDescription ?? 'Textio is your go-to platform for creating, sharing, and managing content with ease.' }}">
    <title>Textio | {{ $title ?? 'Textio' }}</title>

    <!-- Google Tag Manager -->
    <script defer src="https://www.googletagmanager.com/gtm.js?id=GTM-52TLSJ8C"></script>
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
            var f = d.getElementsByTagName(s)[0], j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.defer = true;
            j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-52TLSJ8C');
    </script>

    <!-- Preload critical assets -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" as="style">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('favicon/letter-t-purple.png') }}?v=1">

    <!-- Tailwind CSS (consider using a build process instead of CDN for production) -->
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- <link rel="stylesheet" href="{{ asset('css/styles.css') }}"> <!-- Externalized custom styles --> --}}
    <style>
        /* Import Poppins font */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

body {
    font-family: 'Poppins', sans-serif;
    scroll-behavior: smooth;
}

.hero-slide {
    opacity: 0;
    transition: opacity 1s ease-in-out;
    position: absolute;
    width: 100%;
    height: 100%;
}

.hero-slide.active {
    opacity: 1;
}

.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
}

.add-to-cart-btn {
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.3s ease;
}

.product-card:hover .add-to-cart-btn {
    opacity: 1;
    transform: translateY(0);
}

@media (max-width: 767px) {
    .add-to-cart-btn {
        opacity: 1;
        transform: translateY(0);
    }
}

.category-card {
    transition: all 0.3s ease;
}

.category-card:hover {
    transform: scale(1.05);
}

.nav-link {
    position: relative;
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 0;
    height: 2px;
    background-color: #8b5cf6;
    transition: width 0.3s ease;
}

.nav-link:hover::after {
    width: 100%;
}
    </style>
</head>

<body class="flex flex-col min-h-screen bg-gray-50 font-poppins" x-data="{
    currentSlide: 0,
    cartItems: 0,
    mobileMenuOpen: false,
    accountDropdownOpen: false,
    init() {
        setInterval(() => this.currentSlide = (this.currentSlide + 1) % 3, 5000);
    }
}" x-init="init()">
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-52TLSJ8C" height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>

    <!-- Header -->
    <livewire:public.section.header />

    <!-- Main Content -->
    <main class="lg:pb-0">
        {{ $slot }}
    </main>

    <!-- Mobile Bottom Navbar -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-2 flex justify-between items-center z-50"
         x-data="{ activeButton: '{{ Route::currentRouteName() === 'home' ? 'home' : (Route::currentRouteName() === 'wishlist.index' ? 'wishlist' : (Route::currentRouteName() === 'myCart' ? 'cart' : (auth()->guest() ? 'signin' : 'profile'))) }}' }">
        <a wire:navigate href="{{ route('home') }}" class="text-center p-2"
           :class="{ 'text-purple-600': activeButton === 'home', 'text-gray-500': activeButton !== 'home' }"
           @click="activeButton = 'home'" aria-label="Go to Home">
            <i class="fas fa-home text-lg"></i>
            <span class="text-xs mt-1">Home</span>
        </a>
        <a wire:navigate href="{{ auth()->guest() ? route('login') : route('wishlist.index') }}" class="text-center p-2"
           :class="{ 'text-purple-600': activeButton === 'wishlist', 'text-gray-500': activeButton !== 'wishlist' }"
           @click="activeButton = '{{ auth()->guest() ? 'signin' : 'wishlist' }}'" aria-label="Go to Wishlist">
            <i class="fas fa-heart text-lg"></i>
            <span class="text-xs mt-1">Wishlist</span>
        </a>
        <a wire:navigate href="{{ auth()->guest() ? route('login') : route('myCart') }}" class="text-center p-2"
           :class="{ 'text-purple-600': activeButton === 'cart', 'text-gray-500': activeButton !== 'cart' }"
           @click="activeButton = '{{ auth()->guest() ? 'signin' : 'cart' }}'" aria-label="Go to Cart">
            <i class="fas fa-shopping-bag text-lg"></i>
            <span class="text-xs mt-1">Cart</span>
        </a>
        @guest
            <a wire:navigate href="{{ route('login') }}" class="text-center p-2"
               :class="{ 'text-purple-600': activeButton === 'signin', 'text-gray-500': activeButton !== 'signin' }"
               @click="activeButton = 'signin'" aria-label="Sign In">
                <i class="fas fa-sign-in-alt text-lg"></i>
                <span class="text-xs mt-1">Sign In</span>
            </a>
        @else
            <a wire:navigate href="{{ route('profile-information') }}" class="text-center p-2"
               :class="{ 'text-purple-600': activeButton === 'profile', 'text-gray-500': activeButton !== 'profile' }"
               @click="activeButton = 'profile'" aria-label="Go to Profile">
                <i class="fas fa-user text-lg"></i>
                <span class="text-xs mt-1">Profile</span>
            </a>
        @endguest
    </nav>

    <!-- Footer -->
    @unless (in_array(Route::currentRouteName(), ['myCart', 'myOrder', 'view.product', 'public.product.all']))
        <livewire:public.section.footer />
    @endunless

    @livewireScripts
    <script defer src="{{ asset('js/main.js') }}"></script> <!-- Externalized JavaScript -->
</body>
</html>