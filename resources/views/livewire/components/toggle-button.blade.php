@php
    $sizes = [
        'sm' => [
            'container' => 'w-9 h-5',
            'thumb' => 'w-4 h-4',
            'translate' => 'translate-x-4'
        ],
        'md' => [
            'container' => 'w-11 h-6', 
            'thumb' => 'w-5 h-5',
            'translate' => 'translate-x-5'
        ],
        'lg' => [
            'container' => 'w-14 h-7',
            'thumb' => 'w-6 h-6',
            'translate' => 'translate-x-7'
        ]
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    
    // Define color schemes based on type
    $colorSchemes = [
        'default' => [
            'active' => 'bg-blue-500 focus:ring-blue-500',
            'inactive' => 'bg-gray-300'
        ],
        'success' => [
            'active' => 'bg-green-500 focus:ring-green-500',
            'inactive' => 'bg-gray-300'
        ],
        'warning' => [
            'active' => 'bg-yellow-400 focus:ring-yellow-500',
            'inactive' => 'bg-gray-300'
        ],
        'danger' => [
            'active' => 'bg-red-500 focus:ring-red-500',
            'inactive' => 'bg-gray-300'
        ]
    ];
    
    $colorScheme = $colorSchemes[$type ?? 'default'] ?? $colorSchemes['default'];
@endphp

<div class="flex items-center">
    <button type="button" 
            wire:click="toggle"
            class="group relative inline-flex {{ $sizeClass['container'] }} flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $checked ? $colorScheme['active'] : $colorScheme['inactive'] . ' hover:bg-gray-400' }}"
            role="switch" 
            aria-checked="{{ $checked ? 'true' : 'false' }}"
            @if($id) id="{{ $id }}" @endif>
        <span class="sr-only">{{ $label ?: 'Toggle switch' }}</span>
        <span aria-hidden="true" 
              class="{{ $sizeClass['thumb'] }} pointer-events-none inline-block rounded-full bg-white shadow-lg transform ring-0 transition-all duration-200 ease-in-out group-hover:shadow-xl {{ $checked ? $sizeClass['translate'] : 'translate-x-0' }}"></span>
    </button>
    
    @if($label)
        <label for="{{ $id }}" class="ml-3 text-sm font-medium text-gray-700 cursor-pointer select-none">
            {{ $label }}
        </label>
    @endif
</div>
