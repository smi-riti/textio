<section class="relative h-[300px] md:h-[460px] bg-[#171717] text-white overflow-hidden">
    <!-- Background pattern -->
    <div class="absolute "></div>

    <!-- Content container -->
    <div class="relative container mx-auto px-6 h-full flex flex-col justify-center items-center text-center">
        <!-- Main heading -->
        <h1 class="text-4xl md:text-6xl font-extrabold mb-3 animate-pop-in text-white">
            Your Unique Style
        </h1>

        <!-- Subheading -->
        <p class="text-base text-white md:text-lg max-w-md mb-6 animate-pop-in-delay">
            Shop exclusive, ready-to-wear t-shirts and hoodies designed for you. Easy browsing, fast checkout!
        </p>

        <!-- CTA button -->
        <div class="flex gap-4 animate-pop-in-delay-2">
            <a wire:navigate href="{{route('public.product.all')}}"
               class="bg-[#570674] hover:bg-[#8f4da7] text-white font-semibold py-4 px-8 rounded-full transition duration-300 transform hover:scale-105">
                Browse Collection
            </a>
        </div>
    </div>

    <!-- Decorative element - Curved line -->
    {{-- <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none">
        <svg class="w-full h-10 md:h-14" viewBox="0 0 1200 120" preserveAspectRatio="none" fill="#ffffff33">
            <path d="M0,0V60c100,30,250,45,400,30s300-45,450-15c150,30,250-15,350-45V0Z"/>
        </svg>
    </div> --}}

    <style>
        /* Custom animations */
        @keyframes popIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-pop-in {
            animation: popIn 0.6s ease-out forwards;
        }

        .animate-pop-in-delay {
            opacity: 0;
            animation: popIn 0.6s ease-out 0.2s forwards;
        }

        .animate-pop-in-delay-2 {
            opacity: 0;
            animation: popIn 0.6s ease-out 0.4s forwards;
        }
    </style>
</section>