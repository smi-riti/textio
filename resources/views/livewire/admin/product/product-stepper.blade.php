<div class="bg-white border-b border-gray-200 px-6 py-4 mb-8">
    <nav aria-label="Progress" class="flex justify-center">
        <ol class="flex items-center space-x-8">
            @foreach($steps as $stepNumber => $step)
                <li class="flex items-center">
                    @if($stepNumber < $currentStep || in_array($stepNumber, $completedSteps))
                        <!-- Completed Step -->
                        <button wire:click="goToStep({{ $stepNumber }})" 
                                class="group flex items-center">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-green-600 transition-colors group-hover:bg-green-700">
                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span class="ml-3 text-sm font-medium text-green-600 group-hover:text-green-700">{{ $step['name'] }}</span>
                        </button>
                    @elseif($stepNumber == $currentStep)
                        <!-- Current Step -->
                        <div class="flex items-center">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600">
                                <span class="text-white text-sm font-medium">{{ $stepNumber }}</span>
                            </span>
                            <span class="ml-3 text-sm font-medium text-blue-600">{{ $step['name'] }}</span>
                        </div>
                    @else
                        <!-- Upcoming Step -->
                        <div class="flex items-center">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-gray-300 bg-white">
                                <span class="text-gray-500 text-sm font-medium">{{ $stepNumber }}</span>
                            </span>
                            <span class="ml-3 text-sm font-medium text-gray-500">{{ $step['name'] }}</span>
                        </div>
                    @endif

                    @if(!$loop->last)
                        <!-- Connector -->
                        <div class="ml-8 flex h-0.5 w-16 items-center">
                            <div class="h-0.5 w-full {{ ($stepNumber < $currentStep || in_array($stepNumber, $completedSteps)) ? 'bg-green-600' : 'bg-gray-300' }}"></div>
                        </div>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>

    <!-- Step Description -->
    <div class="text-center mt-4">
        <p class="text-sm text-gray-600">
            Step {{ $currentStep }} of {{ count($steps) }}: {{ $steps[$currentStep]['description'] }}
        </p>
    </div>

    <!-- Navigation Buttons -->
    <div class="flex justify-between mt-6 px-4 sm:px-0">
        @if($currentStep > 1)
            <button type="button" wire:click="previousStep" 
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                Next
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        @endif
    </div>
</div>
