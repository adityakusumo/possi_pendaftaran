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

                    {{-- Form for radio buttons --}}
                    <form id="competition-type-form">
                        @csrf {{-- CSRF token for security --}}
                        <div class="space-y-4">
                            {{-- Radio Button: Kota/Kab --}}
                            <div class="flex items-center">
                                <input id="jenis_kejuaraan_kota_kab" name="jenis_kejuaraan" type="radio" value="K" data-description="ANTAR KOTA"
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:bg-gray-700 dark:border-gray-600"
                                    {{ (isset($currentKompetisiSetting) && $currentKompetisiSetting->JNSKOMPETISI === 'K') ? 'checked' : '' }}>
                                <label for="jenis_kejuaraan_kota_kab" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Kota/Kab') }}
                                </label>
                            </div>

                            {{-- Radio Button: Club --}}
                            <div class="flex items-center">
                                <input id="jenis_kejuaraan_club" name="jenis_kejuaraan" type="radio" value="C" data-description="ANTAR CLUB"
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:bg-gray-700 dark:border-gray-600"
                                    {{ (isset($currentKompetisiSetting) && $currentKompetisiSetting->JNSKOMPETISI === 'C') ? 'checked' : '' }}>
                                <label for="jenis_kejuaraan_club" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Club') }}
                                </label>
                            </div>

                            {{-- Radio Button: Provinsi --}}
                            <div class="flex items-center">
                                <input id="jenis_kejuaraan_provinsi" name="jenis_kejuaraan" type="radio" value="P" data-description="ANTAR PROVINSI"
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:bg-gray-700 dark:border-gray-600"
                                    {{ (isset($currentKompetisiSetting) && $currentKompetisiSetting->JNSKOMPETISI === 'P') ? 'checked' : '' }}>
                                <label for="jenis_kejuaraan_provinsi" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Provinsi') }}
                                </label>
                            </div>
                        </div>
                    </form>

                    {{-- Success/Error Message Display --}}
                    <div id="status-message" class="mt-4 p-3 rounded-md text-center hidden"></div>

                    {{-- You can add more settings segments here --}}

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('competition-type-form');
            const radioButtons = form.querySelectorAll('input[name="jenis_kejuaraan"]');
            const statusMessage = document.getElementById('status-message');

            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    const typeCode = this.value;
                    const typeDescription = this.dataset.description; // Get description from data attribute

                    // Clear previous messages and hide
                    statusMessage.classList.add('hidden');
                    statusMessage.textContent = '';
                    statusMessage.classList.remove('bg-green-100', 'text-green-600', 'bg-red-100', 'text-red-600'); // Clear styling

                    // Send AJAX request using Axios (already available in Breeze/Jetstream)
                    axios.post('{{ route('competition_settings.update_type') }}', {
                        type_code: typeCode,
                        type_description: typeDescription,
                        _token: '{{ csrf_token() }}' // Include CSRF token for security
                    })
                    .then(response => {
                        statusMessage.textContent = response.data.message || 'Settings updated successfully!';
                        statusMessage.classList.add('bg-green-100', 'text-green-600'); // Success styling
                        statusMessage.classList.remove('hidden'); // Show message
                        console.log('Success:', response.data);
                    })
                    .catch(error => {
                        const errorMessage = error.response && error.response.data && error.response.data.message
                                            ? error.response.data.message
                                            : 'An error occurred while updating settings.';
                        statusMessage.textContent = errorMessage;
                        statusMessage.classList.add('bg-red-100', 'text-red-600'); // Error styling
                        statusMessage.classList.remove('hidden'); // Show message
                        console.error('Error:', error.response ? error.response.data : error);
                    });
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
