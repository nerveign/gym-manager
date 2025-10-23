@props([
    'firstItem' => null,
    'lastItem' => null,
    'total' => null,
    'onFirstPage' => false,
    'hasMorePages' => false,
    'previousPageUrl' => null,
    'nextPageUrl' => null,
    'lastPage' => 1,
    'currentPage' => 1,
    'pageUrl' => null, 
])

@if($total > 0)
<div class="mt-6 flex items-center justify-between">
    <div class="text-sm text-gray-700">
        Showing {{ $firstItem }} to {{ $lastItem }} of {{ $total }} results
    </div>

    <div class="flex space-x-2">
        <!-- Previous -->
        @if($onFirstPage)
            <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                <i class="fas fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $previousPageUrl }}" class="px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-chevron-left"></i>
            </a>
        @endif

        <!-- Pages -->
        @foreach(range(1, $lastPage) as $page)
            @if($page == $currentPage)
                <span class="px-3 py-1 bg-blue-600 text-white rounded-lg">{{ $page }}</span>
            @else
                <a href="{{ $pageUrl($page) }}" class="px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        <!-- Next -->
        @if($hasMorePages)
            <a href="{{ $nextPageUrl }}" class="px-3 py-1 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                <i class="fas fa-chevron-right"></i>
            </span>
        @endif
    </div>
</div>
@endif
