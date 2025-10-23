<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-2">Welcome, {{ auth()->user()->name }}! 👋</h3>
                    <p class="text-gray-600">Manage your gym activities and track your progress.</p>
                </div>
            </div>

            <div class="flex flex-col gap-4" >
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center gap-2">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                               <!-- logo -->
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Membership Status</p>
                                <p class="text-lg font-semibold text-gray-900">
                                    @if($activeMembership)
                                        <span class="text-green-600">Active</span>
                                    @else
                                        <span class="text-red-600">Inactive</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enrolled Classes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center gap-2">
                            <div class="p-3 rounded-full bg-green-100 text-green-500">
                                <!-- logo -->
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Enrolled Classes</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $enrolledClasses }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Bookings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center gap-2">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                               <!-- logo -->
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Upcoming Bookings</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $upcomingBookings }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Progress -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <div class="flex justify-between" >
                        
                        <h3 class="text-lg font-semibold mb-4">Recent Progress</h3>
                        <a class="text-zinc-500" href="{{ route('customer.progress.index') }}">
                            view all progress
                        </a>
                    </div>
                    @if($recentProgress->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentProgress as $progress)
                                <div class="flex justify-between items-center" >
                                    <div>
                                        <p class="font-medium">{{ $progress->exercise }}</p>
                                        <p class="text-sm text-gray-600">{{ $progress->duration }} minutes</p>
                                        <p class="text-sm text-gray-500">{{ $progress->description }}</p>
                                    </div>
                                    <div class="flex gap-3" >
                                        <div class="h-3 flex justify-center items-center" >
                                            <a href="{{ route('customer.progress.edit', $progress) }}" 
                                               class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="h-3 flex justify-center items-center" >
                                            <form action="{{ route('customer.progress.destroy', $progress) }}" method="POST" class="inline">
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
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No recent progress recorded.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>