<div>
    <div class="min-h-screen bg-gray-50">
        <!-- Hero Section -->
        <div class="bg-accent text-white py-20">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Get In Touch</h1>
                <p class="text-xl md:text-2xl opacity-90">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
            </div>
        </div>

        <div class="container mx-auto px-4 py-16">
            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Contact Information -->
                <div class="space-y-8">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800 mb-6">Contact Information</h2>
                        <p class="text-gray-600 text-lg">Reach out to us through any of the following channels. We're here to help!</p>
                    </div>

                    <!-- Contact Cards -->
                    <div class="grid sm:grid-cols-2 gap-6">
                        <!-- Phone -->
                        <div class="bg-white rounded-2xl p-6 hover:bg-gray-50 transition-all duration-300 border border-gray-200">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-phone text-purple-600 text-xl"></i>
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-2">Phone</h3>
                            <p class="text-gray-600">+91 98765 43210</p>
                            <p class="text-gray-600">+91 87654 32109</p>
                        </div>

                        <!-- Email -->
                        <div class="bg-white rounded-2xl p-6 hover:bg-gray-50 transition-all duration-300 border border-gray-200">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-envelope text-blue-600 text-xl"></i>
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-2">Email</h3>
                            <p class="text-gray-600">info@textio.com</p>
                            <p class="text-gray-600">support@textio.com</p>
                        </div>

                        <!-- Address -->
                        <div class="bg-white rounded-2xl p-6 hover:bg-gray-50 transition-all duration-300 border border-gray-200 sm:col-span-2">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-map-marker-alt text-green-600 text-xl"></i>
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-2">Address</h3>
                            <p class="text-gray-600">123 Business Park, Sector 18,<br>Gurgaon, Haryana 122015, India</p>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="bg-white rounded-2xl p-6 border border-gray-200">
                        <h3 class="font-semibold text-gray-800 mb-4">Follow Us</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white hover:bg-blue-700 transition-colors duration-300">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="w-12 h-12 bg-pink-600 rounded-full flex items-center justify-center text-white hover:bg-pink-700 transition-colors duration-300">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="w-12 h-12 bg-blue-400 rounded-full flex items-center justify-center text-white hover:bg-blue-500 transition-colors duration-300">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="w-12 h-12 bg-blue-800 rounded-full flex items-center justify-center text-white hover:bg-blue-900 transition-colors duration-300">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="w-12 h-12 bg-red-600 rounded-full flex items-center justify-center text-white hover:bg-red-700 transition-colors duration-300">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Map -->
                    <div class="bg-white rounded-2xl p-6 border border-gray-200">
                        <h3 class="font-semibold text-gray-800 mb-4">Find Us</h3>
                        <div class="bg-gray-100 rounded-xl h-64 overflow-hidden">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3507.7956741025944!2d77.04823931508232!3d28.481108982467967!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390d194f2f54a4b5%3A0x9136c03af4d8a3a5!2sSector%2018%2C%20Gurugram%2C%20Haryana!5e0!3m2!1sen!2sin!4v1645123456789!5m2!1sen!2sin" 
                                class="w-full h-full rounded-xl border-0" 
                                allowfullscreen="" 
                                loading="lazy">
                            </iframe>
                        </div>
                    </div>
                </div>

                <!-- Enquiry Form -->
                <div class="bg-white rounded-2xl p-8 border border-gray-200">
                    <div class="mb-6">
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">Send us a Message</h2>
                        <p class="text-gray-600">Fill out the form below and we'll get back to you within 24 hours.</p>
                    </div>
                    
                    @if($successMessage)
                        <div class="bg-green-50 border-l-4 border-green-400 text-green-700 px-4 py-3 rounded-r-lg mb-6" 
                             x-data="{ show: true }" 
                             x-show="show" 
                             x-init="setTimeout(() => show = false, 5000)"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform translate-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-3 text-lg"></i>
                                <div>
                                    <p class="font-medium">Success!</p>
                                    <p class="text-sm">{{ $successMessage }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form wire:submit="submitEnquiry" class="space-y-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                wire:model="name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 @error('name') border-red-500 ring-1 ring-red-500 @enderror"
                                placeholder="Enter your full name">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Email Address 
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                wire:model="email"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 @error('email') border-red-500 ring-1 ring-red-500 @enderror"
                                placeholder="Enter your email address">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number 
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="tel" 
                                id="phone" 
                                wire:model="phone"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 @error('phone') border-red-500 ring-1 ring-red-500 @enderror"
                                placeholder="Enter your phone number">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">
                                Subject 
                            </label>
                            <input 
                                type="text" 
                                id="subject" 
                                wire:model="subject"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 @error('subject') border-red-500 ring-1 ring-red-500 @enderror"
                                placeholder="Enter the subject">
                            @error('subject')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                                Message <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="message" 
                                wire:model="message"
                                rows="5"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 resize-none @error('message') border-red-500 ring-1 ring-red-500 @enderror"
                                placeholder="Enter your message..."></textarea>
                            @error('message')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            class="w-full bg-accent text-white py-4 px-6 rounded-xl font-semibold hover:bg-purple-700 focus:bg-purple-700 transition-all duration-300 focus:ring-4 focus:ring-purple-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                            wire:loading.attr="disabled"
                            wire:target="submitEnquiry">
                            
                            <span wire:loading.remove wire:target="submitEnquiry" class="flex items-center">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Send Message
                            </span>
                            
                            {{-- <span wire:loading wire:target="submitEnquiry" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sending...
                            </span> --}}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Quick Stats Section -->
        {{-- <div class="bg-purple-600 text-white py-16">
            <div class="container mx-auto px-4">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold mb-2">Why Choose Textio?</h3>
                    <p class="text-purple-100">Your trusted partner for custom printing solutions</p>
                </div>
                <div class="grid md:grid-cols-4 gap-8 text-center">
                    <div class="bg-purple-700 rounded-2xl p-6">
                        <div class="text-3xl font-bold mb-2">24/7</div>
                        <div class="text-sm text-purple-100">Customer Support</div>
                    </div>
                    <div class="bg-purple-700 rounded-2xl p-6">
                        <div class="text-3xl font-bold mb-2">1000+</div>
                        <div class="text-sm text-purple-100">Happy Customers</div>
                    </div>
                    <div class="bg-purple-700 rounded-2xl p-6">
                        <div class="text-3xl font-bold mb-2">5+</div>
                        <div class="text-sm text-purple-100">Years Experience</div>
                    </div>
                    <div class="bg-purple-700 rounded-2xl p-6">
                        <div class="text-3xl font-bold mb-2">100%</div>
                        <div class="text-sm text-purple-100">Quality Assured</div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</div>