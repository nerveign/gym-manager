<section class="space-y-6">
    <div class="mb-4">
        <h2 class="text-xl font-semibold text-red-600">Delete Account</h2>
        <p class="mt-1 text-sm text-gray-600">
            Once your account is deleted, all of its resources and data will be permanently deleted.
        </p>
    </div>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-600 hover:bg-red-700 text-white rounded-lg px-6 py-2.5 transition duration-150 ease-in-out"
    >
        <i class="fas fa-trash-alt mr-2"></i> {{ __('Delete Account') }}
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" maxWidth="md" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full border border-gray-300 rounded-lg p-2.5"
                    placeholder="{{ __('Your Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')" type="button">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Confirm Delete') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>