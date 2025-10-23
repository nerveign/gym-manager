<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Progress Tracking') }}
            </h2>
            <a href="{{ route('customer.progress.create') }}" 
               class="px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                Add Progress
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($progress->count() > 0)
                <div class="flex flex-col gap-3" >
                    @foreach($progress as $item)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-4 mb-3">
                                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $item->exercise }}</h3>
                                                <div class="flex items-center space-x-4 mt-1">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        {{ $item->duration }} minutes
                                                    </span>
                                                    <span class="text-sm text-gray-500">
                                                        {{ $item->created_at->format('M j, Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if($item->description)
                                            <p class="text-gray-600 mt-3 bg-gray-50 p-3 rounded-lg">
                                                {{ $item->description }}
                                            </p>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center space-x-2 ml-4">
                                        <a href="{{ route('customer.progress.edit', $item) }}" 
                                           class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('customer.progress.destroy', $item) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Are you sure you want to delete this progress record?')"
                                                    class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-colors duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No progress records yet</h3>
                        <p class="text-gray-500 mb-6">Start tracking your fitness journey by adding your first progress record.</p>
                        <a href="{{ route('customer.progress.create') }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center space-x-2 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span>Add Your First Progress</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>