<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrintMagic - Custom Printing</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    }">
    <!-- Header -->
   <livewire:public.section.header/>

    <!-- Hero Section with Carousel -->
   <livewire:public.section.hero-section/>

    <!-- Featured Categories Section -->
   <livewire:public.section.categories/>

    <!-- Featured Products Section -->
    <livewire:public.section.products/>

    <!-- Additional Products from Screenshot -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-semibold text-center mb-12">More Custom Products</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Sticker Product -->
                <div class="product-card bg-white rounded-lg overflow-hidden shadow-md">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1584824486539-53bb4646bdbc?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=687&q=80" 
                             alt="Custom Stickers" class="w-full h-56 object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1">Custom Stickers</h3>
                        <p class="text-gray-600 text-sm mb-3">Waterproof and durable</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-semibold text-purple-600">₹9.95 - ₹39.95</span>
                            </div>
                            <button class="add-to-cart-btn bg-purple-100 text-purple-600 hover:bg-purple-600 hover:text-white py-1 px-3 rounded-full text-sm transition duration-300"
                                    @click="cartItems++">
                                <i class="fas fa-shopping-cart mr-1"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Badge Product -->
                <div class="product-card bg-white rounded-lg overflow-hidden shadow-md">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1616634375264-2d2e17736a36?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=715&q=80" 
                             alt="Custom Badges" class="w-full h-56 object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1">Custom Badges</h3>
                        <p class="text-gray-600 text-sm mb-3">Pin-back or magnetic</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-semibold text-purple-600">₹9.90 - ₹49.90</span>
                            </div>
                            <button class="add-to-cart-btn bg-purple-100 text-purple-600 hover:bg-purple-600 hover:text-white py-1 px-3 rounded-full text-sm transition duration-300"
                                    @click="cartItems++">
                                <i class="fas fa-shopping-cart mr-1"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Phone Case Product -->
                <div class="product-card bg-white rounded-lg overflow-hidden shadow-md">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=880&q=80" 
                             alt="Phone Cases" class="w-full h-56 object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1">Phone Cases</h3>
                        <p class="text-gray-600 text-sm mb-3">For all phone models</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-semibold text-purple-600">₹19.95 - ₹24.95</span>
                            </div>
                            <button class="add-to-cart-btn bg-purple-100 text-purple-600 hover:bg-purple-600 hover:text-white py-1 px-3 rounded-full text-sm transition duration-300"
                                    @click="cartItems++">
                                <i class="fas fa-shopping-cart mr-1"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Mouse Pad Product -->
                <div class="product-card bg-white rounded-lg overflow-hidden shadow-md">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1596461404969-9ae70f2830c1?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" 
                             alt="Mouse Pads" class="w-full h-56 object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1">Mouse Pads</h3>
                        <p class="text-gray-600 text-sm mb-3">Non-slip rubber base</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-semibold text-purple-600">₹14.90 - ₹99.90</span>
                            </div>
                            <button class="add-to-cart-btn bg-purple-100 text-purple-600 hover:bg-purple-600 hover:text-white py-1 px-3 rounded-full text-sm transition duration-300"
                                    @click="cartItems++">
                                <i class="fas fa-shopping-cart mr-1"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-12 bg-purple-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-semibold mb-4">Stay Updated</h2>
            <p class="mb-8 max-w-2xl mx-auto">Subscribe to our newsletter for exclusive deals, new product announcements, and design tips.</p>
            
            <form class="max-w-md mx-auto flex flex-col md:flex-row gap-4">
                <input type="email" placeholder="Your email address" class="flex-grow px-4 py-3 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-300">
                <button type="submit" class="bg-white text-purple-600 font-semibold px-6 py-3 rounded-lg hover:bg-gray-100 transition duration-300">Subscribe</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
   <livewire:public.section.footer/>
</body>
</html>