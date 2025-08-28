 <header class="bg-white shadow-md sticky top-0 z-50">
     <div class="container mx-auto px-4">
         <div class="py-4 flex items-center justify-between">
             <a wire:navigate href="{{route('home')}}" class="text-2xl sm:text-3xl font-semibold text-purple-600">Tex<span
                     class="text-gray-800">tio</span></a>

             <div class="hidden md:flex items-center space-x-8">
                 <nav class="flex space-x-8">
                     <a href="#" class="nav-link text-gray-700 hover:text-purple-600 font-medium">T-Shirts</a>
                     <a href="#" class="nav-link text-gray-700 hover:text-purple-600 font-medium">Hoodies</a>
                     <a href="#" class="nav-link text-gray-700 hover:text-purple-600 font-medium">Mugs</a>
                     <a href="#" class="nav-link text-gray-700 hover:text-purple-600 font-medium">Posters</a>
                     <a href="#" class="nav-link text-gray-700 hover:text-purple-600 font-medium">All Products</a>
                 </nav>
             </div>

             <div class="flex items-center space-x-4">
                 <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden">
                     <i class="fas fa-bars text-xl"></i>
                 </button>
                 <div class="hidden md:flex items-center space-x-6">
                     <a href="#" class="flex items-center hover:text-purple-600">
                         <i class="far fa-heart text-xl"></i>
                     </a>
                     <div class="relative">
                         <button @click="accountDropdownOpen = !accountDropdownOpen"
                             class="flex items-center hover:text-purple-600">
                             <i class="far fa-user text-xl"></i>
                         </button>
                         <div x-show="accountDropdownOpen" @click.outside="accountDropdownOpen = false"
                             class="absolute right-0 mt-2 py-2 w-48 bg-white shadow-lg rounded-lg z-40">
                             <a href="{{route('register')}}" class="block px-4 py-2 hover:bg-gray-100">Sign In</a>
                             <a href="#" class="block px-4 py-2 hover:bg-gray-100">Register</a>
                             <a href="#" class="block px-4 py-2 hover:bg-gray-100">My Orders</a>
                         </div>
                     </div>
                     <div class="relative">
                         <a href="#" class="flex items-center hover:text-purple-600">
                             <i class="fas fa-shopping-cart text-xl"></i>
                             <span x-text="cartItems"
                                 class="absolute -top-2 -right-2 bg-purple-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"></span>
                         </a>
                     </div>
                 </div>
             </div>
         </div>

         <!-- Mobile Menu -->
         <div x-show="mobileMenuOpen" class="md:hidden py-4 border-t border-gray-200">
             <nav class="flex flex-col space-y-4">
                 <a href="#" class="text-gray-700 hover:text-purple-600 font-medium">T-Shirts</a>
                 <a href="#" class="text-gray-700 hover:text-purple-600 font-medium">Hoodies</a>
                 <a href="#" class="text-gray-700 hover:text-purple-600 font-medium">Mugs</a>
                 <a href="#" class="text-gray-700 hover:text-purple-600 font-medium">Posters</a>
                 <a href="#" class="text-gray-700 hover:text-purple-600 font-medium">All Products</a>
             </nav>
         </div>
     </div>
 </header>
