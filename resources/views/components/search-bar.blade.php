@props([
    'action' => '',
    'placeholder' => 'Search...',
    'value' => request('search')
])

<div class="mb-6">
    <form method="GET" action="{{ $action }}" class="flex gap-2">
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input 
                type="text" 
                name="search" 
                value="{{ $value }}"
                placeholder="{{ $placeholder }}" 
                class="text-sm pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
            >
        </div>
        <button 
            type="submit"
            class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition-colors duration-200"
        >
            Search
        </button>
        @if($value)
            <a 
                href="{{ $action }}"
                class="px-4 text-sm py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200"
            >
                Clear
            </a>
        @endif
    </form>
</div>