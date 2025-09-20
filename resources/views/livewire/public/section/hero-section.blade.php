 <section class="relative h-96 md:h-[500px] bg-primary text-white overflow-hidden">
     
             <!-- Background pattern -->
             <div
                 class="absolute inset-0 bg-[radial-gradient(#8f4da755_1px,transparent_2px)] [background-size:24px_24px]">
             </div>

             <!-- Content container -->
             <div class="relative container mx-auto px-4 h-full flex flex-col justify-center items-center text-center">
                 <!-- Main heading -->
                 <h1 class="text-4xl md:text-6xl font-bold mb-4 animate-fade-in">
                     Premium <span class="text-accent">Printing</span> Services
                 </h1>

                 <!-- Subheading -->
                 <p class="text-lg md:text-xl max-w-2xl mb-8 animate-fade-in-delay">
                     Custom printing on t-shirts, mugs, caps and more. Bring your ideas to life with our high-quality
                     printing solutions.
                 </p>

                 <!-- CTA buttons -->
                 <div class="flex flex-col sm:flex-row gap-4 animate-fade-in-delay-2">
                     <a wire:navigate href="{{route('public.product.all')}}"
                         class="bg-accent hover:bg-[#7c4190] text-white font-semibold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-105">
                         Explore Mores
                     </a>
                     <a wire:navigate href="{{route('contact')}}"
                         class="border-2 border-white hover:border-accent hover:bg-accent/10 font-semibold py-3 px-8 rounded-lg transition duration-300">
                         Get a Quote
                     </a>
                 </div>
             </div>

             <!-- Decorative elements -->
             <div class="absolute bottom-0 left-0 w-full flex justify-between items-end px-10 opacity-70">
                 <!-- Left graphic - T-shirt -->
                 <div class="transform translate-y-6 hidden md:block">
                     <svg width="120" height="120" viewBox="0 0 512 512" class="fill-accent">
                         <path
                             d="M256 96c-13.25 0-24 10.75-24 24s10.75 24 24 24 24-10.75 24-24-10.75-24-24-24zm80 144c0-26.5-21.5-48-48-48h-64c-26.5 0-48 21.5-48 48v192h160V240zM336 96h-32.9c-7.52-17.05-24.66-28.57-43.82-28.13-21.02.5-38.25 17.78-39.28 38.72C219.5 128.2 237.8 148 262 148h32.9c8.02 0 14.31-7.1 13.93-15.1-.38-8.01-6.99-14.9-14.93-14.9h-1.8c-13.25 0-24-10.75-24-24s10.75-24 24-24 24 10.75 24 24c0 8.84 7.16 16 16 16h32c8.84 0 16-7.16 16-16 0-26.51-21.49-48-48-48z" />
                     </svg>
                 </div>

                 <!-- Right graphic - Printing machine -->
                 <div class="transform translate-y-4 scale-90 hidden md:block">
                     <svg width="140" height="140" viewBox="0 0 512 512" class="fill-accent">
                         <path
                             d="M128 192h256v64H128v-64zM448 64H64C28.65 64 0 92.65 0 128v192c0 35.35 28.65 64 64 64h48v80c0 4.25 2.75 8 6.75 9.38C121.4 453.4 123.2 453.8 125 453.8c3 0 6-1.25 8.25-3.5L224 368h224c35.35 0 64-28.65 64-64V128C512 92.65 483.3 64 448 64zM464 304c0 8.822-7.178 16-16 16H113.2l-40.8 40.8V352H64c-8.822 0-16-7.178-16-16V128c0-8.822 7.178-16 16-16h384c8.822 0 16 7.178 16 16V304z" />
                     </svg>
                 </div>
             </div>

         <style>
             /* Custom animations */
             @keyframes fadeIn {
                 from {
                     opacity: 0;
                     transform: translateY(20px);
                 }

                 to {
                     opacity: 1;
                     transform: translateY(0);
                 }
             }

             .animate-fade-in {
                 animation: fadeIn 1s ease-out forwards;
             }

             .animate-fade-in-delay {
                 opacity: 0;
                 animation: fadeIn 1s ease-out 0.3s forwards;
             }

             .animate-fade-in-delay-2 {
                 opacity: 0;
                 animation: fadeIn 1s ease-out 0.6s forwards;
             }
         </style>
     
 </section>
