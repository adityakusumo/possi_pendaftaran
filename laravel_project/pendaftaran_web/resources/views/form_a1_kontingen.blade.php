<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Entri Kontingen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-full">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">{{ __('Form Entri Kontingen') }}</h3>

                    <form>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="jenis_kompetisi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Jenis Kompetisi') }}</label>
                                <select id="jenis_kompetisi" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" disabled>
                                    @foreach($jenisKompetisiOptions as $code => $description)
                                    <option value="{{ $code }}"
                                        {{ (isset($currentKompetisiSetting) && $currentKompetisiSetting->JNSKOMPETISI === $code) ? 'selected' : '' }}>
                                        {{ $description }}
                                    </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('*Sesuai dengan pengaturan kompetisi') }}</p>
                            </div>

                            {{-- KONDISI UNTUK NAMA KONTINGEN (disembunyikan jika JNSKOMPETISI = P) --}}
                            <div id="div_nama_kontingen" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                <label for="nama_kontingen" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nama Kontingen') }}</label>
                                <select id="nama_kontingen" name="nama_kontingen"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 select2-writable"
                                    data-placeholder="{{ __('Pilih atau ketik nama kontingen') }}">
                                </select>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('*Bisa dipilih dari dropdown menu & bisa diketik juga') }}</p>
                            </div>

                            {{-- KONDISI UNTUK JENIS KOTA/KAB (disembunyikan jika JNSKOMPETISI = P) --}}
                            <div class="mt-4" id="div_jenis_kota_kab" style="display: none;">
                                <label for="jenis_kota_kab" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Jenis Kota/Kab') }}</label>
                                <input type="text" id="jenis_kota_kab" name="jenis_kota_kab"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    readonly>
                            </div>

                            {{-- KONDISI UNTUK NAMA KOTA/KAB (disembunyikan jika JNSKOMPETISI = P) --}}
                            <div class="mt-4" id="div_nama_kota_kab" style="display: none;">
                                <label for="nama_kota_kab" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nama Kota/Kab') }}</label>
                                <input type="text" id="nama_kota_kab" name="nama_kota_kab"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    readonly>
                            </div>

                            {{-- PROVINSI --}}
                            <div class="mt-4" id="div_provinsi_input" style="display: none;">
                                <label for="provinsi_input" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Provinsi') }}</label>
                                <input type="text" id="provinsi_input" name="provinsi_input"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    readonly>
                            </div>
                            <div class="mt-4">
                                <label for="negara" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Negara') }}</label>
                                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="negara" name="negara" value="INDONESIA" readonly>
                            </div>
                            <div>
                                <label for="contact_person" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Contact Person (CP)') }}</label>
                                <input type="text" id="contact_person" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="telepon" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Telepon') }}</label>
                                <input type="tel" id="telepon" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="jumlah_official" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Jumlah Official') }}</label>
                                <input type="number" id="jumlah_official" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="mt-6">
                            <h4 class="font-semibold mb-2 text-gray-900 dark:text-gray-100">{{ __('Daftar Kontingen') }}</h4>
                            <div class="overflow-x-auto rounded-lg shadow">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Nama Kontingen') }}</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Propinsi') }}</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Kota/Kab') }}</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Nama Kota/Kab') }}</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Negara') }}</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Contact Person(CP)') }}</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Telepon') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">Indonesia</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-4">
                            <button type="button" class="rounded-md bg-gray-200 dark:bg-gray-700 px-4 py-2 text-sm font-semibold text-gray-800 dark:text-gray-300 shadow-sm hover:bg-gray-300 dark:hover:bg-gray-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ __('Keluar') }}</button>
                            <button type="button" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">{{ __('Hapus') }}</button>
                            <button type="button" class="rounded-md bg-yellow-400 px-4 py-2 text-sm font-semibold text-gray-800 shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-500">{{ __('Batal') }}</button>
                            <button type="button" class="rounded-md bg-blue-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500">{{ __('Ubah') }}</button>
                            <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ __('Simpan') }}</button>
                            <button type="button" class="rounded-md bg-green-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500">{{ __('Tambah') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            console.log('Document ready. Initializing Form A1 Kontingen scripts.');

            var jnsKompetisi = '{{ $jnsKompetisi }}';
            console.log('Current Jenis Kompetisi (from Blade):', jnsKompetisi);

            var appliedMode = @json($appliedMode); // 1 for enabled, 2 for disabled
            var autoSelectedClubValue = @json($autoSelectedClubValue);
            var autoFillDetails = @json($autoFillDetails);
            var userRole = @json($userRoleString ?? 'user');

            console.log('DEBUG: Value of $appliedMode from Blade:', appliedMode);
            console.log('DEBUG: Value of $autoSelectedClubValue from Blade:', autoSelectedClubValue);
            console.log('DEBUG: Value of $autoFillDetails from Blade:', autoFillDetails);
            console.log('DEBUG: Value of $userRoleString from Blade:', userRole);

            console.log('User Club Name:', autoSelectedClubValue);
            console.log('User Role:', userRole);
            console.log('User Club Details:', autoFillDetails);


            var namaKontingenElement = $('#nama_kontingen');
            var namaKotaKabElement = $('#nama_kota_kab');
            var provinsiInputElement = $('#provinsi_input');
            var jenisKotaKabElement = $('#jenis_kota_kab');

            // Data from MstClub (for jnsKompetisi 'C' and general lookups)
            var namaClubsMstClubData = @json($namaClubsMstClub ?? []);
            var mstClubDetails = @json($mstClubDetails ?? []);

            // Data from PilihanPesertaKotaKab (for jnsKompetisi 'K' and 'P' and general lookups)
            var namaClubsPilihanPesertaData = @json($namaClubsPilihanPeserta ?? []);
            var namaPropinsiPilihanPesertaData = @json($namaPropinsiPilihanPeserta ?? []);
            var pilihanPesertaKotaKabDetails = @json($pilihanPesertaKotaKabDetails ?? []);

            // DEBUG CONSOLE LOGS FOR SELECT2 DATA SOURCES (keep these for now)
            console.log('DEBUG: namaClubsMstClubData for Select2:', namaClubsMstClubData);
            console.log('DEBUG: namaClubsPilihanPesertaData for Select2:', namaClubsPilihanPesertaData);
            console.log('DEBUG: namaPropinsiPilihanPesertaData for Select2:', namaPropinsiPilihanPesertaData);

            var isSettingNamaKontingenValue = false;

            // Helper function to initialize Select2
            // We will add a 'clear' argument here
            function initSelect2(element, data, placeholder, allowTags = true, isMultiple = false, isDisabled = false, clearPrevious = true) {
                if (element.length) {
                    if (element.data('select2') && clearPrevious) { // Only destroy if we want to clear previous state
                        element.select2('destroy');
                        console.log('Destroyed existing Select2 for:', element.attr('id'));
                    }
                    element.select2({
                        tags: allowTags,
                        placeholder: placeholder,
                        allowClear: true,
                        data: data,
                        multiple: isMultiple,
                        disabled: isDisabled,
                        createTag: function(params) {
                            var term = $.trim(params.term);
                            if (term === '') {
                                return null;
                            }
                            return {
                                id: term,
                                text: term,
                                newTag: true
                            };
                        },
                        matcher: function(params, data) {
                            if ($.trim(params.term) === '') {
                                return data;
                            }
                            if (typeof data.text === 'undefined') {
                                return null;
                            }
                            if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1 ||
                                data.id.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                                return data;
                            }
                            return null;
                        }
                    });
                    console.log('Select2 initialized successfully for:', element.attr('id') + ' with disabled status: ' + isDisabled);
                } else {
                    console.warn('Element not found for Select2 initialization:', element.attr('id'));
                }
            }

            // Centralized function to handle selection and detail update
            function handleNamaKontingenSelection(selectedIdValue, detailsSource, jenisPropName, kotaPropName, propinsiPropName) {
                if (isSettingNamaKontingenValue) {
                    return;
                }
                isSettingNamaKontingenValue = true;

                var normalizedSelectedId = (selectedIdValue || '').toUpperCase();
                var detail = detailsSource[normalizedSelectedId];

                // Ensure the selected option is actually in the Select2 data
                var optionExists = namaKontingenElement.find("option[value='" + normalizedSelectedId + "']").length > 0;
                if (!optionExists && normalizedSelectedId) {
                    // If it doesn't exist, add it. This is more for tag creation, but safe here.
                    var newOption = new Option(normalizedSelectedId, normalizedSelectedId, true, true);
                    namaKontingenElement.append(newOption);
                }

                // This is the key line to update Select2's visual display
                namaKontingenElement.val(normalizedSelectedId).trigger('change');
                namaKontingenElement.trigger('select2:close'); // Close dropdown after selection

                console.log('Nama Kontingen (after forceful set) val():', namaKontingenElement.val());
                console.log('Nama Kontingen (after forceful set) data():', namaKontingenElement.select2('data'));

                if (detail) {
                    console.log('Details found:', detail);
                    showAndEnableDetailFields();

                    jenisKotaKabElement.val((detail[jenisPropName] || '').toUpperCase());
                    namaKotaKabElement.val((detail[kotaPropName] || '').toUpperCase());
                    provinsiInputElement.val((detail[propinsiPropName] || '').toUpperCase());
                } else {
                    console.warn('Details not found for:', normalizedSelectedId);
                    hideAndClearDetailFields();
                }

                isSettingNamaKontingenValue = false;
            }

            // Helper functions for UI state
            function hideAndClearDetailFields() {
                $('#div_jenis_kota_kab').hide();
                $('#div_nama_kota_kab').hide();
                $('#div_provinsi_input').hide();
                jenisKotaKabElement.prop('disabled', true).val('');
                namaKotaKabElement.prop('disabled', true).val('');
                provinsiInputElement.prop('disabled', true).val('');
            }

            function showAndEnableDetailFields() {
                $('#div_jenis_kota_kab').show();
                $('#div_nama_kota_kab').show();
                $('#div_provinsi_input').show();
                jenisKotaKabElement.prop('disabled', false);
                namaKotaKabElement.prop('disabled', false);
                provinsiInputElement.prop('disabled', false);
            }

            // --- Initial UI State Setup ---
            $('#div_nama_kontingen').show(); // Ensure kontingen field is shown

            // Main Logic based on appliedMode
            if (appliedMode === 1) { // Mode 1: Enabled (Admin, Operator, Special User)
                console.log('Applied Mode 1: Nama Kontingen combo box enabled.');

                // Ensure previous event handlers are removed before adding new ones for Mode 1
                namaKontingenElement.off('change');

                if (jnsKompetisi === 'C') {
                    initSelect2(namaKontingenElement, namaClubsMstClubData, 'Pilih Nama Club', true, false, false);
                } else if (jnsKompetisi === 'K') {
                    initSelect2(namaKontingenElement, namaClubsPilihanPesertaData, 'Pilih Nama Kota/Kab', true, false, false);
                } else if (jnsKompetisi === 'P') {
                    initSelect2(namaKontingenElement, namaPropinsiPilihanPesertaData, 'Pilih Nama Provinsi', true, false, false);
                }

                // Attach the change handler *after* initialization and off()
                namaKontingenElement.on('change', function(e) {
                    if (isSettingNamaKontingenValue) {
                        return;
                    }

                    var selectedData = namaKontingenElement.select2('data')[0];
                    var selectedId = selectedData ? selectedData.id : '';

                    if (jnsKompetisi === 'C') {
                        handleNamaKontingenSelection(selectedId, mstClubDetails, 'JENIS', 'NAMAKOTA', 'NAMAPROP');
                    } else if (jnsKompetisi === 'K') {
                        handleNamaKontingenSelection(selectedId, pilihanPesertaKotaKabDetails, 'JENIS', 'NAMAKOTA', 'NAMAPROPINSI');
                    } else if (jnsKompetisi === 'P') {
                        // For 'P', no details, just select the province name
                        handleNamaKontingenSelection(selectedId, {}, '', '', ''); // Pass empty details for 'P'
                        hideAndClearDetailFields(); // And hide fields
                    }
                });

                // Clear any pre-selected value from previous loads for Admin mode
                namaKontingenElement.val(null).trigger('change');
                hideAndClearDetailFields(); // Initially hide details for Admin until selection

            } else { // Mode 2: Disabled (Regular User)
                console.log('Applied Mode 2: Nama Kontingen combo box disabled and auto-selected.');

                // Ensure previous event handlers are removed for Mode 2
                namaKontingenElement.off('change'); // Ensure no lingering change handlers

                hideAndClearDetailFields();

                var autoSelectData = [];
                if (autoSelectedClubValue) {
                    autoSelectData = [{
                        id: autoSelectedClubValue,
                        text: autoSelectedClubValue
                    }];
                }

                // For Mode 2, we don't want to clear data because we're force-setting it
                initSelect2(namaKontingenElement, autoSelectData, autoSelectedClubValue || 'N/A', false, false, true, false); // clearPrevious = false

                if (autoSelectedClubValue) {
                    namaKontingenElement.val(autoSelectedClubValue).trigger('change');

                    if (autoFillDetails) {
                        showAndEnableDetailFields();
                        jenisKotaKabElement.val(autoFillDetails.JENIS || '');
                        namaKotaKabElement.val(autoFillDetails.NAMAKOTA || '');
                        provinsiInputElement.val(autoFillDetails.NAMAPROP || autoFillDetails.NAMAPROPINSI || '');
                    } else {
                        hideAndClearDetailFields();
                    }
                } else {
                    namaKontingenElement.val(null).trigger('change');
                    hideAndClearDetailFields();
                }
            }

            // For JNSKOMPETISI P, ensure detail fields are always hidden regardless of mode
            // This is a final override for 'P' type
            if (jnsKompetisi === 'P') {
                hideAndClearDetailFields();
            }
        });
    </script>
    @endpush
</x-app-layout>
