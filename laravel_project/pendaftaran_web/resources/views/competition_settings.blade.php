<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Competition Settings') }}
        </h2>
        <!-- {{-- Navigation links consistent with navigation.blade.php --}}
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
        </div> -->
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

                    {{-- NEW: Wajib Nias Section --}}
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-6 mb-4">
                        {{ __('Wajib Nias') }}
                    </h3>

                    <form id="wajib-nias-form">
                        @csrf
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input id="wajib_nias_true" name="wajib_nias" type="radio" value="1"
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:bg-gray-700 dark:border-gray-600"
                                    {{ (isset($currentKompetisiSetting) && $currentKompetisiSetting->WAJIBNIAS == 1) ? 'checked' : '' }}>
                                <label for="wajib_nias_true" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Wajib') }}
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input id="wajib_nias_false" name="wajib_nias" type="radio" value="0"
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:bg-gray-700 dark:border-gray-600"
                                    {{ (isset($currentKompetisiSetting) && $currentKompetisiSetting->WAJIBNIAS == 0) ? 'checked' : '' }}>
                                <label for="wajib_nias_false" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Tidak Wajib') }}
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
        document.addEventListener('DOMContentLoaded', function() {
            // Get elements for Competition Type
            const competitionTypeForm = document.getElementById('competition-type-form');
            const competitionTypeRadioButtons = competitionTypeForm.querySelectorAll('input[name="jenis_kejuaraan"]');

            // Get elements for Wajib Nias
            const wajibNiasForm = document.getElementById('wajib-nias-form');
            const wajibNiasRadioButtons = wajibNiasForm.querySelectorAll('input[name="wajib_nias"]');

            // Get the shared status message element
            const statusMessage = document.getElementById('status-message');

            // Function to display status messages
            function showStatusMessage(message, type = 'success') {
                statusMessage.textContent = message;
                statusMessage.classList.remove('hidden', 'bg-green-100', 'text-green-600', 'bg-red-100', 'text-red-600');
                if (type === 'success') {
                    statusMessage.classList.add('bg-green-100', 'text-green-600');
                } else {
                    statusMessage.classList.add('bg-red-100', 'text-red-600');
                }
            }

            // --- Competition Type (Jenis Kejuaraan) Logic ---
            competitionTypeRadioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    const typeCode = this.value;
                    const typeDescription = this.dataset.description;

                    // Clear previous messages and hide
                    statusMessage.classList.add('hidden');
                    statusMessage.textContent = '';
                    statusMessage.classList.remove('bg-green-100', 'text-green-600', 'bg-red-100', 'text-red-600');

                    axios.post('{{ route('competition_settings.update_type') }}', {
                                type_code: typeCode,
                                type_description: typeDescription,
                                _token: '{{ csrf_token() }}'
                            })
                        .then(response => {
                            showStatusMessage(response.data.message || 'Competition type updated successfully!', 'success');
                            console.log('Success (Competition Type):', response.data);
                        })
                        .catch(error => {
                            const errorMessage = error.response && error.response.data && error.response.data.message ?
                                error.response.data.message :
                                'An error occurred while updating competition type.';
                            showStatusMessage(errorMessage, 'error');
                            console.error('Error (Competition Type):', error.response ? error.response.data : error);
                        });
                });
            });

            // --- NEW: Wajib Nias Logic with Console Logs ---
            console.log('JS DEBUG: Document ready. Initializing Wajib Nias logic.'); // Debug point 1

            console.log('JS DEBUG: wajibNiasForm element:', wajibNiasForm); // Debug point 2
            if (!wajibNiasForm) {
                console.error('JS ERROR: Form with ID "wajib-nias-form" was not found! Check your HTML ID.'); // Debug point 2.1
                return; // Stop script if the form isn't found
            }

            console.log('JS DEBUG: wajibNiasRadioButtons NodeList:', wajibNiasRadioButtons); // Debug point 3
            if (wajibNiasRadioButtons.length === 0) {
                console.warn('JS WARNING: No "Wajib Nias" radio buttons found within the form. Check their name attribute or if the form is correctly rendered.'); // Debug point 3.1
            }


            wajibNiasRadioButtons.forEach(radio => {
                console.log('JS DEBUG: Attaching listener to Wajib Nias radio with ID:', radio.id, 'and value:', radio.value); // Debug point 4
                radio.addEventListener('change', function() {
                    console.log('JS DEBUG: Wajib Nias radio button changed! Initiating AJAX. Value:', this.value); // Debug point 5
                    const wajibNiasValue = this.value; // Will be '0' or '1'

                    // Clear previous messages and hide
                    statusMessage.classList.add('hidden');
                    statusMessage.textContent = '';
                    statusMessage.classList.remove('bg-green-100', 'text-green-600', 'bg-red-100', 'text-red-600');

                    axios.post('{{ route('competition_settings.update_wajib_nias') }}', {
                                wajib_nias: wajibNiasValue,
                                _token: '{{ csrf_token() }}'
                            })
                        .then(response => {
                            showStatusMessage(response.data.message || 'Wajib Nias setting updated successfully!', 'success');
                            console.log('Success (Wajib Nias):', response.data); // Debug point 6 (success)
                        })
                        .catch(error => {
                            const errorMessage = error.response && error.response.data && error.response.data.message ?
                                error.response.data.message :
                                'An error occurred while updating Wajib Nias setting.';
                            showStatusMessage(errorMessage, 'error');
                            console.error('Error (Wajib Nias):', error.response ? error.response.data : error); // Debug point 6 (error)
                        });
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
