<footer class="bg-[#171717] text-white pt-12 pb-8">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
            <!-- Brand Section -->
            <div>
                <a class="flex items-center mb-5" href="/" wire:navigate>
                    <span class="text-2xl font-bold bg-gradient-to-r from-[#8f4da7] to-purple-500 bg-clip-text text-transparent">TEXTIO</span>
                </a>
                <p class="text-gray-400 mb-5 text-sm leading-relaxed">Custom printing on demand for all your needs. Create unique products with your designs.</p>
                <div class="flex space-x-4">
                    <a href="https://www.facebook.com/share/1FBm4FK3Ud/" class="w-9 h-9 rounded-full bg-[#2a2a2a] flex items-center justify-center text-gray-400 hover:bg-[#8f4da7] hover:text-white transition-colors duration-300">
                        <i class="fab fa-facebook-f text-sm"></i>
                    </a>
                   
                    <a href="https://www.instagram.com/textio_llp/" class="w-9 h-9 rounded-full bg-[#2a2a2a] flex items-center justify-center text-gray-400 hover:bg-[#8f4da7] hover:text-white transition-colors duration-300">
                        <i class="fab fa-instagram text-sm"></i>
                    </a>
                   
                </div>
            </div>

            <!-- Shop Links -->
            <div>
                <h3 class="text-lg font-semibold mb-5 pb-2 border-b border-[#8f4da7] inline-block">Shop</h3>
                <ul class="space-y-3">
                  
                    
                    <li><a href="{{route('public.product.all')}}" class="text-gray-400 hover:text-[#8f4da7] transition-colors duration-300 text-sm flex items-center">
                        <span class="w-1 h-1 bg-[#8f4da7] rounded-full mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Explore Products
                    </a></li>
                </ul>
            </div>

            <!-- Company Links -->
            <div>
                <h3 class="text-lg font-semibold mb-5 pb-2 border-b border-[#8f4da7] inline-block">Company</h3>
                <ul class="space-y-3">
                    <li><a wire:navigate href="{{route('about')}}" class="text-gray-400 hover:text-[#8f4da7] transition-colors duration-300 text-sm flex items-center">
                        <span class="w-1 h-1 bg-[#8f4da7] rounded-full mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        About Us
                    </a></li>
                    <li><a wire:navigate href="{{route('contact')}}" class="text-gray-400 hover:text-[#8f4da7] transition-colors duration-300 text-sm flex items-center">
                        <span class="w-1 h-1 bg-[#8f4da7] rounded-full mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Contact Us
                    </a></li>
                  
                    <li><a href="{{route('privacy')}}" class="text-gray-400 hover:text-[#8f4da7] transition-colors duration-300 text-sm flex items-center">
                        <span class="w-1 h-1 bg-[#8f4da7] rounded-full mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Privacy Policy
                    </a></li>
                    <li><a wire:navigate href="{{route('conditions')}}" class="text-gray-400 hover:text-[#8f4da7] transition-colors duration-300 text-sm flex items-center">
                        <span class="w-1 h-1 bg-[#8f4da7] rounded-full mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Terms of Service
                    </a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-semibold mb-5 pb-2 border-b border-[#8f4da7] inline-block">Contact</h3>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <div class="w-8 h-8 rounded-full bg-[#2a2a2a] flex items-center justify-center mr-3 flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-[#8f4da7] text-sm"></i>
                        </div>
                        <span class="text-gray-400 text-sm">First floor YPN
Purnea Bihar 854301</span>
                    </li>
                    <li class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-[#2a2a2a] flex items-center justify-center mr-3 flex-shrink-0">
                            <i class="fas fa-phone-alt text-[#8f4da7] text-sm"></i>
                        </div>
                        <span class="text-gray-400 text-sm">+91 90605 73350  </span>
                    </li>
                    <li class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-[#2a2a2a] flex items-center justify-center mr-3 flex-shrink-0">
                            <i class="fas fa-envelope text-[#8f4da7] text-sm"></i>
                        </div>
                        <span class="text-gray-400 text-sm">textiollp@gmail.com</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-gray-800 pt-6 text-center">
            <p class="text-gray-400 text-sm">&copy; 2025 Textio. All rights reserved.</p>
        </div>
    </div>
</footer>