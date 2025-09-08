 <section class="relative h-96 md:h-[500px] bg-gray-900 text-white overflow-hidden">
        <!-- Slide 1 -->
        <div class="hero-slide" :class="{ 'active': currentSlide === 0 }" 
            style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1523381210434-271e8be1f52b?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80'); background-size: cover; background-position: center;">
            <div class="container mx-auto px-4 h-full flex items-center">
                <div class="max-w-2xl">
                    <h1 class="text-4xl md:text-5xl font-semibold mb-4">Custom Printed T-Shirts</h1>
                    <p class="text-xl mb-8">Design your own t-shirts with your logo, art, or text. High quality printing with fast turnaround.</p>
                    <a href="#" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-8 rounded-lg inline-block">Shop Now</a>
                </div>
            </div>
        </div>
        
        <!-- Slide 2 -->
        <div class="hero-slide" :class="{ 'active': currentSlide === 1 }" 
            style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1588200908342-23b585c03e26?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80'); background-size: cover; background-position: center;">
            <div class="container mx-auto px-4 h-full flex items-center">
                <div class="max-w-2xl">
                    <h1 class="text-4xl md:text-5xl font-semibold mb-4">Personalized Mugs & Drinkware</h1>
                    <p class="text-xl mb-8">Create custom mugs for your business, event, or as gifts. Dishwasher safe and vibrant prints.</p>
                    <a href="#" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-8 rounded-lg inline-block">Create Now</a>
                </div>
            </div>
        </div>
        
        <!-- Slide 3 -->
        <div class="hero-slide" :class="{ 'active': currentSlide === 2 }" 
            style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1611010344444-5f9e4d86a6e1?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1632&q=80'); background-size: cover; background-position: center;">
            <div class="container mx-auto px-4 h-full flex items-center">
                <div class="max-w-2xl">
                    <h1 class="text-4xl md:text-5xl font-semibold mb-4">Custom Branded Merchandise</h1>
                    <p class="text-xl mb-8">Elevate your brand with custom printed merchandise. Perfect for corporate gifts and promotions.</p>
                    <a href="#" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-8 rounded-lg inline-block">Explore</a>
                </div>
            </div>
        </div>
    
        <!-- Carousel Controls -->
        <button @click="currentSlide = (currentSlide - 1 + 3) % 3" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button @click="currentSlide = (currentSlide + 1) % 3" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full">
            <i class="fas fa-chevron-right"></i>
        </button>
        
        <!-- Indicators -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <button @click="currentSlide = 0" class="w-3 h-3 rounded-full" :class="currentSlide === 0 ? 'bg-white' : 'bg-white bg-opacity-50'"></button>
            <button @click="currentSlide = 1" class="w-3 h-3 rounded-full" :class="currentSlide === 1 ? 'bg-white' : 'bg-white bg-opacity-50'"></button>
            <button @click="currentSlide = 2" class="w-3 h-3 rounded-full" :class="currentSlide === 2 ? 'bg-white' : 'bg-white bg-opacity-50'"></button>
        </div>
    </section>
