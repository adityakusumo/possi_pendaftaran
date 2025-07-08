<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Competition Settings') }}
        </h2>
        {{-- Navigation links consistent with navigation.blade.php --}}
        <div class="mt-4 flex space-x-4">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-nav-link>
            <x-nav-link :href="route('settings')" :active="request()->routeIs('settings')">
                {{ __('User Settings') }}
            </x-nav-link>
            {{-- This page itself --}}
            <x-nav-link :href="route('competition_settings')" :active="request()->routeIs('competition_settings')">
                {{ __('Competition Settings') }}
            </x-nav-link>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Main content area --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Competition Type (Jenis Kejuaraan)') }}
                    </h3>

                    <div class="space-y-4"> {{-- Adds vertical spacing between radio buttons --}}
                        {{-- Radio Button: Kota/Kab --}}
                        <div class="flex items-center">
                            <input id="jenis_kejuaraan_kota_kab" name="jenis_kejuaraan" type="radio" value="Kota/Kab"
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                            <label for="jenis_kejuaraan_kota_kab" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Kota/Kab') }}
                            </label>
                        </div>

                        {{-- Radio Button: Club --}}
                        <div class="flex items-center">
                            <input id="jenis_kejuaraan_club" name="jenis_kejuaraan" type="radio" value="Club"
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                            <label for="jenis_kejuaraan_club" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Club') }}
                            </label>
                        </div>

                        {{-- Radio Button: Provinsi --}}
                        <div class="flex items-center">
                            <input id="jenis_kejuaraan_provinsi" name="jenis_kejuaraan" type="radio" value="Provinsi"
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                            <label for="jenis_kejuaraan_provinsi" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Provinsi') }}
                            </label>
                        </div>
                    </div>

                    {{-- You can add more settings segments here --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
