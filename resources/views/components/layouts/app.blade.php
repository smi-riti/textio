<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Textio - Custom Printing</title>
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
        .font-poppins {
        font-family: 'Poppins', sans-serif;
    }
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    </style>
</head>
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
    <livewire:public.section.header/>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

  

   
    <!-- Footer -->
    <livewire:public.section.footer/>

    @livewireScripts
    <script>
        function handleNewsletterSubmit() {
            alert('Thank you for subscribing!');
            // Add actual submission logic (e.g., API call) here
        }
    </script>
</body>
</html>