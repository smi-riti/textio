<div class="bg-white rounded-2xl  p-6 mb-8 border border-gray-100">
    <!-- Progress Steps -->
    <nav aria-label="Progress">
        <ol class="flex items-center justify-between">
            @foreach($steps as $stepNumber => $step)
                <li class="relative flex items-center flex-1">
                    <div class="flex flex-col items-center w-full">
                        @if($stepNumber < $currentStep || in_array($stepNumber, $completedSteps))
                            <!-- Completed Step -->
                            <button wire:click="goToStep({{ $stepNumber }})" 
                                    class="group flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#8f4da7] transition-all duration-300 hover:bg-[#7a3f90] hover:shadow-lg shadow-md">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <span class="absolute top-full mt-2 text-xs font-medium text-[#8f4da7] group-hover:text-[#7a3f90] whitespace-nowrap hidden sm:block">{{ $step['name'] }}</span>
                        @elseif($stepNumber == $currentStep)
                            <!-- Current Step -->
                            <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-[#171717] shadow-lg ring-2 ring-offset-2 ring-[#171717] ring-opacity-30">
                                <span class="text-white text-sm font-medium">{{ $stepNumber }}</span>
                            </div>
                            <span class="absolute top-full mt-2 text-xs font-medium text-[#171717] whitespace-nowrap hidden sm:block">{{ $step['name'] }}</span>
                        @else
                            <!-- Upcoming Step -->
                            <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-full border-2 border-gray-200 bg-white shadow-sm">
                                <span class="text-gray-500 text-sm font-medium">{{ $stepNumber }}</span>
                            </div>
                            <span class="absolute top-full mt-2 text-xs font-medium text-gray-500 whitespace-nowrap hidden sm:block">{{ $step['name'] }}</span>
                        @endif
                    </div>
                    
                    @if(!$loop->last)
                        <!-- Connector -->
                        <div class="absolute top-1/2 left-2/3 sm:left-3/4 w-full -translate-y-1/2 z-0">
                            <div class="h-1 w-full {{ ($stepNumber < $currentStep || in_array($stepNumber, $completedSteps)) ? 'bg-[#8f4da7]' : 'bg-gray-200' }} rounded-full"></div>
                        </div>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>

    <!-- Step Description -->
    <div class="text-center mt-8 sm:mt-10 px-2">
        <p class="text-sm text-gray-600 font-medium bg-gray-50 py-2 px-4 rounded-lg inline-block">
            Step {{ $currentStep }} of {{ count($steps) }}: <span class="text-[#171717]">{{ $steps[$currentStep]['description'] }}</span>
        </p>
    </div>

    <!-- Navigation Buttons -->
    <div class="flex justify-between mt-8 px-2">
        @if($currentStep > 1)
            <button type="button" wire:click="previousStep" 
                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-[#171717] bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#8f4da7] focus:ring-opacity-30 hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Previous
            </button>
        @else
            <div></div>
        @endif

        @if($currentStep < count($steps))
            <button type="button" wire:click="nextStep" 
                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-[#171717] rounded-xl shadow-sm hover:bg-[#2a2a2a] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#8f4da7] focus:ring-opacity-30 hover:shadow-md">
                Next
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        @endif
    </div>
</div>