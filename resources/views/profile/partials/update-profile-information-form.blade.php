<section>
    {{-- Header disederhanakan karena sudah ada header di dalam tab --}}
    <div class="mb-4">
        <h2 class="text-xl font-bold text-gray-900">Personal Information</h2>
        <p class="mt-1 text-sm text-gray-600">
            Update your account's profile details and contact information.
        </p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Gunakan layout 2-kolom --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            
            {{-- Name --}}
            <div>
                <x-input-label for="name" :value="__('Full Name')" />
                <x-text-input 
                    id="name" 
                    name="name" 
                    type="text" 
                    class="mt-1 block w-full border border-gray-300 rounded-lg p-2.5" 
                    :value="old('name', $user->name)" 
                    required 
                    autofocus 
                    autocomplete="name" 
                />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            {{-- Phone Number --}}
            <div>
                <x-input-label for="phone" value="Phone Number" />
                <x-text-input 
                    id="phone" 
                    name="phone" 
                    type="text" 
                    class="mt-1 block w-full border border-gray-300 rounded-lg p-2.5" 
                    :value="old('phone', $user->phone)"
                    autocomplete="tel"
                />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

        </div>

        {{-- Email dan Role di buat 2 kolom --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4">
            
            {{-- Email --}}
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full border border-gray-300 rounded-lg p-2.5" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            {{-- Role --}}
            <div>
                <x-input-label for="role" value="User Role" />
                <x-text-input id="role" name="role" type="text" class="mt-1 block w-full bg-gray-50 border border-gray-300 rounded-lg p-2.5" :value="old('role', ucfirst($user->role))" disabled />
                <p class="mt-1 text-xs text-gray-500">Role cannot be changed here.</p>
            </div>
            
        </div>
        
        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2">
                <p class="text-sm text-gray-800">
                    {{ __('Your email address is unverified.') }}

                    <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            </div>
        @endif


        <div class="flex items-center gap-4 pt-4">
            <x-primary-button class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-4 py-2 transition duration-150 ease-in-out">{{ __('Save Changes') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>