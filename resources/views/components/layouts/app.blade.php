<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Textio - Custom Printing</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
             tailwind.config = {
                 theme: {
                     extend: {
                         colors: {
                             primary: '#171717',
                             accent: '#8f4da7',
                         }
                     }
                 }
             }
         </script>
    <style>
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

        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="flex flex-col min-h-screen bg-gray-50" x-data="{
    currentSlide: 0,
    cartItems: 0,
    mobileMenuOpen: false,
    accountDropdownOpen: false,
    init() {
        setInterval(() => {
            this.currentSlide = (this.currentSlide + 1) % 3;
        }, 5000);
    }
}" x-init="init()">
    <!-- Header -->
    <livewire:public.section.header />

    <!-- Main Content -->
    <main class=" lg:pb-0">
        {{ $slot }}
    </main>

    <!-- Mobile bottom navbar -->
   <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-2 flex justify-between items-center z-50"
    x-data="{ activeButton: '{{ Route::currentRouteName() === 'home' ? 'home' : (Route::currentRouteName() === 'wishlist.index' ? 'wishlist' : (Route::currentRouteName() === 'myCart' ? 'cart' : (auth()->guest() ? 'signin' : 'profile'))) }}' }">
    
    <!-- Home Button -->
    <a wire:navigate href="{{ route('home') }}" class="text-center p-2"
        :class="{ 'text-purple-600': activeButton === 'home', 'text-gray-500': activeButton !== 'home' }"
        @click.stop="activeButton = 'home'">
        <i class="fas fa-home text-lg"></i>
        <p class="text-xs mt-1">Home</p>
    </a>

    <!-- Wishlist Button -->
    <a wire:navigate href="{{ auth()->guest() ? route('login') : route('wishlist.index') }}" class="text-center p-2"
        :class="{ 'text-purple-600': activeButton === 'wishlist', 'text-gray-500': activeButton !== 'wishlist' }"
        @click.stop="activeButton = '{{ auth()->guest() ? 'signin' : 'wishlist' }}'">
        <i class="fas fa-heart text-lg"></i>
        <p class="text-xs mt-1">Wishlist</p>
    </a>

    <!-- Cart Button -->
    <a wire:navigate href="{{ auth()->guest() ? route('login') : route('myCart') }}" class="text-center p-2"
        :class="{ 'text-purple-600': activeButton === 'cart', 'text-gray-500': activeButton !== 'cart' }"
        @click.stop="activeButton = '{{ auth()->guest() ? 'signin' : 'cart' }}'">
        <i class="fas fa-shopping-bag text-lg"></i>
        <p class="text-xs mt-1">Cart</p>
    </a>

    <!-- Sign In / Profile Button -->
    @guest
        <a wire:navigate href="{{ route('login') }}" class="text-center p-2"
            :class="{ 'text-purple-600': activeButton === 'signin', 'text-gray-500': activeButton !== 'signin' }"
            @click.stop="activeButton = 'signin'">
            <i class="fas fa-sign-in-alt text-lg"></i>
            <p class="text-xs mt-1">Sign In</p>
        </a>
    @else
        <a wire:navigate href="{{ route('profile-information') }}" class="text-center p-2"
            :class="{ 'text-purple-600': activeButton === 'profile', 'text-gray-500': activeButton !== 'profile' }"
            @click.stop="activeButton = 'profile'">
            <i class="fas fa-user text-lg"></i>
            <p class="text-xs mt-1">Profile</p>
        </a>
    @endguest
</div>

    <!-- Footer -->
@if (!in_array(Route::currentRouteName(), ['myCart', 'myOrder','view.product']))
        <livewire:public.section.footer />
    @endif
    @livewireScripts
    <script>
        function handleNewsletterSubmit() {
            alert('Thank you for subscribing!');
            // Add actual submission logic (e.g., API call) here
        }

        // Ensure mobile menu is closed on page load
        document.addEventListener('livewire:initialized', () => {
            // Find the Header component by name
            const headerComponent = window.Livewire.components.components().find(c => c.name === 'public.section.header');
            if (headerComponent) {
                headerComponent.set('mobileMenuOpen', false);
            }
        });
    </script>
</body>

</html>