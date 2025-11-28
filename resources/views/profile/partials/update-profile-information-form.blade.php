<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- 1. FOTO PROFIL (LAYOUT VERTIKAL) --}}
        <div>
            <x-input-label for="image" :value="__('Photo Profile')" />

            {{-- Div pembungkus tanpa 'flex' agar elemen turun ke bawah --}}
            <div class="mt-3">

                {{-- A. Preview Foto --}}
                <div class="mb-4"> {{-- Memberi jarak antara foto dan tombol upload --}}
                    @if($user->image_url)
                    <img src="{{ $user->image_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover border border-gray-200 shadow-sm">
                    @else
                    <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-3xl font-bold border border-gray-300">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    @endif
                </div>

                {{-- B. Input File --}}
                <input type="file" name="image" accept="image/*"
                    class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-indigo-50 file:text-indigo-700
                    hover:file:bg-indigo-100
                    cursor-pointer focus:outline-none" />
            </div>

            <p class="mt-2 text-xs text-gray-500">JPG, PNG, or GIF (Max. 2MB).</p>
            <x-input-error class="mt-2" :messages="$errors->get('image')" />
        </div>

        {{-- 2. GRID 2 KOLOM (Nama & No HP) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="name" :value="__('Full Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="phone" :value="__('Phone Number')" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>
        </div>

        {{-- 3. GRID 2 KOLOM (Email & Role) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

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
            </div>

            <div>
                <x-input-label for="role" :value="__('User Role')" />
                <x-text-input id="role" type="text" class="mt-1 block w-full bg-gray-100 text-gray-500 cursor-not-allowed" :value="ucfirst($user->role)" disabled />
                <p class="mt-1 text-xs text-gray-500">Role cannot be changed manually.</p>
            </div>
        </div>

        {{-- AREA AKSI (NOTIFIKASI & TOMBOL) --}}
        <div class="pt-6">
            @if (session('status') === 'profile-updated')
            <div
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 3000)"
                class="mb-2">
                <p class="text-sm font-medium text-green-600 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i> Data profil berhasil disimpan.
                </p>
            </div>
            @endif

            <div class="flex items-center gap-4">
                <x-primary-button class="bg-green-600 hover:bg-green-700 text-white border-transparent">
                    {{ __('Save Changes') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</section>