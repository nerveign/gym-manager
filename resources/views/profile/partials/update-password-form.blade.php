<section>
    {{-- Header disederhanakan karena sudah ada header di dalam tab --}}
    <div class="mb-4">
        <h2 class="text-xl font-semibold text-gray-900">Update Password</h2>
        <p class="mt-1 text-sm text-gray-600">
            Ensure your account is using a long, random password to stay secure.
        </p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="current_password" :value="__('Current Password')" />
            <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full border border-gray-300 rounded-lg p-2.5" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('New Password')" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full border border-gray-300 rounded-lg p-2.5" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full border border-gray-300 rounded-lg p-2.5" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end gap-4 pt-4">
            <x-primary-button class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-4 py-2 transition duration-150 ease-in-out">{{ __('Update Password') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>