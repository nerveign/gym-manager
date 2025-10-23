<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Progress') }}
            </h2>
            <a href="{{ route('customer.progress.index') }}" 
               class="text-gray-600 hover:text-gray-900 font-medium flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back to Progress</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6">
                    <form action="{{ route('customer.progress.store') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Exercise Field -->
                            <div>
                                <label for="exercise" class="block text-sm font-medium text-gray-700 mb-2">
                                    Exercise Type *
                                </label>
                                <input type="text" 
                                       name="exercise" 
                                       id="exercise"
                                       value="{{ old('exercise') }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                       placeholder="e.g., Chest Workout, Running, Yoga..."
                                       maxlength="255">
                                @error('exercise')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Duration Field -->
                            <div>
                                <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
                                    Duration (minutes) *
                                </label>
                                <input type="number" 
                                       name="duration" 
                                       id="duration"
                                       value="{{ old('duration') }}"
                                       required
                                       min="1"
                                       max="300"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                       placeholder="e.g., 45">
                                @error('duration')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description Field -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Workout Details
                                </label>
                                <textarea name="description" 
                                          id="description"
                                          rows="4"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                          placeholder="Describe your workout, sets, reps, weights used, or how you felt..."
                                          maxlength="1000">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-end space-x-4 pt-6">
                                <a href="{{ route('customer.dashboard') }}" 
                                   class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Save Progress</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>