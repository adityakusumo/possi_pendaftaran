<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Nama Club --}}
        <div class="mt-4">
            <x-input-label for="club_IDCLUB" :value="__('Nama Club')" />
            <select id="club_IDCLUB" name="club_IDCLUB" required
                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">{{ __('Pilih Nama Club') }}</option> {{-- Placeholder option --}}
                @foreach($clubs as $club)
                <option value="{{ $club->IDCLUB }}" {{ old('club_IDCLUB') == $club->IDCLUB ? 'selected' : '' }}>
                    {{ $club->NAMACLUB }}
                </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('club_IDCLUB')" class="mt-2" />
        </div>

        {{-- Kota/Kab Domisili Dropdown --}}
        <div class="mt-4">
            <x-input-label for="kota_kab_dom" :value="__('Kota / Kab Domisili')" />
            <select id="kota_kab_dom" name="kota_kab_dom" required
                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">{{ __('Pilih Kota / Kabupaten Domisili') }}</option> {{-- Placeholder option --}}
                @foreach($kotaKabDomOptions as $kotaKabDom)
                <option value="{{ $kotaKabDom }}" {{ old('kota_kab_dom') == $kotaKabDom ? 'selected' : '' }}>
                    {{ $kotaKabDom }}
                </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('kota_kab_dom')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                type="password"
                name="password"
                required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    {{-- Script to initialize Select2 --}}
    <script>
        // Ensure jQuery and Select2 are loaded before initializing
        $(document).ready(function() {
            $('#club_IDCLUB').select2({
                placeholder: "{{ __('Pilih Nama Club') }}",
                allowClear: true // Allows clearing the selection
            });

            $('#kota_kab_dom').select2({
                placeholder: "{{ __('Pilih Kota / Kabupaten Domisili') }}",
                allowClear: true // Allows clearing the selection
            });
        });
    </script>
</x-guest-layout>
