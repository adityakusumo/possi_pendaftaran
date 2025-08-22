<x-app-layout>
    <x-slot name="title">{{ __('Entri Form A3 - Nomor Estafet') }}</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Form A3 - Nomor Estafet') }}
        </h2>
    </x-slot>

    <!-- {{-- Pass athlete data to JavaScript --}}
    <script>
        window.atletDetails = @json($atletDetailsForJs ?? []);
        console.log('Atlet Details for JS:', window.atletDetails); // For debugging
        // Pass current user's email to JavaScript
        window.currentUserEmail = "{{ Auth::user()->email ?? '' }}";
        window.existingA3Entries = @json($existingA3Entries);
    </script> -->

    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-full">
                    <form id="form-a3-estafet" action="{{ route('form_a3.saveEstafet') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Left Section --}}
                            <div class="md:col-span-2 space-y-4">
                                {{-- Nama Regu Section --}}
                                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Nama Regu') }}</h3>
                                    <div class="flex flex-wrap items-center gap-4">
                                        {{-- KU --}}
                                        <label for="ku_select_estafet" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('KU') }}</label>
                                        <select id="ku_select_estafet" name="ku_select_estafet" class="rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">-- Pilih KU --</option>
                                            {{-- Options will be dynamically loaded or passed from controller --}}
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                            <option value="E">E</option>
                                            <option value="F">F</option>
                                        </select>
                                        {{-- Gender --}}
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Gender') }}</label>
                                        <div class="flex gap-2">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="gender_estafet" value="PA" id="gender_estafet_pa" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Pa') }}</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="gender_estafet" value="PI" id="gender_estafet_pi" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Pi') }}</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="gender_estafet" value="MIX" id="gender_estafet_mix" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Mix') }}</span>
                                            </label>
                                        </div>

                                        {{-- SP / Bukan SP --}}
                                        <div class="flex gap-2">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="sp_status_estafet" value="SP" id="sp_yes" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('SP') }}</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="sp_status_estafet" value="BUKAN_SP" id="sp_no" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Bukan SP') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="flex flex-col sm:flex-row items-center gap-4 mb-4">
                                        <div class="flex-1 w-full">
                                            <!-- <label for="nama_regu" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nama Regu') }}</label> -->
                                            <input type="text" id="nama_regu" name="nama_regu"
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                                            <!-- <label for="nama_atlet_input" class="sr-only">{{ __('Nama Atlet') }}</label>
                                            <select name="selected_atlet_nonias" id="nama_atlet_input"
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">Pilih Atlet</option>
                                                @foreach($NamaAtletList as $idAtlet => $atletName)
                                                <option value="{{ $idAtlet }}">{{ $atletName }}</option>
                                                @endforeach
                                            </select> -->
                                        </div>
                                        <button type="button" id="pilih-regu-button"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                                            {{ __('Pilih') }}
                                        </button>
                                    </div>
                                </div>

                                {{-- Club/Location Details Section --}}
                                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- <div>
                                            <label for="nama_club" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nama Club') }}</label>
                                            <input type="text" id="nama_club" name="nama_club" readonly
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div> -->
                                        <div>
                                            <label for="kota_kab" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Kota / Kab') }}</label>
                                            <input type="text" id="kota_kab" name="kota_kab" value="{{ $kotaKab }}" readonly
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div class="col-span-2">
                                            <label for="nama_kota_kab" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nama Kota / Kab') }}</label>
                                            <input type="text" id="nama_kota_kab" name="nama_kota_kab" value="{{ $namaKotaKab }}" readonly
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label for="propinsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Propinsi') }}</label>
                                            <input type="text" id="propinsi" name="propinsi" value="{{ $propinsi }}" readonly
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label for="negara" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Negara') }}</label>
                                            <input type="text" id="negara" name="negara" value="{{ $negara }}" readonly
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                    </div>
                                    {{-- Competition Type Radios --}}
                                    <div class="mt-4 flex flex-wrap gap-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="SP_type" value="SP_TANPA_NIAS"
                                                {{ $defaultSpType === 'SP_TANPA_NIAS' ? 'checked' : '' }}
                                                disabled
                                                class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('SP Jika Tanpa NIAS') }}</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="SP_type" value="BEBAS"
                                                {{ $defaultSpType === 'BEBAS' ? 'checked' : '' }}
                                                disabled
                                                class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Bebas') }}</span>
                                        </label>
                                    </div>
                                    <div class="mt-4 flex flex-wrap gap-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="competition_type" value="ANTAR_CLUB"
                                                {{ $defaultCompetitionType === 'ANTAR_CLUB' ? 'checked' : '' }}
                                                disabled
                                                class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Antar Club') }}</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="competition_type" value="ANTAR_KOTAKAB"
                                                {{ $defaultCompetitionType === 'ANTAR_KOTAKAB' ? 'checked' : '' }}
                                                disabled
                                                class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Antar Kota/Kab') }}</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="competition_type" value="ANTAR_PROPINSI"
                                                {{ $defaultCompetitionType === 'ANTAR_PROPINSI' ? 'checked' : '' }}
                                                disabled
                                                class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Antar Propinsi') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- {{-- Large Empty Area (for future use, e.g., selected estafet events) --}}
                                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm h-64 mb-4"> {{-- Added mb-4 for spacing --}}
                                    <p class="text-gray-500 dark:text-gray-400 text-center py-10">{{ __('Area untuk daftar nomor estafet yang dipilih.') }}</p>
                                </div> -->

                                {{-- UPDATED CONTAINER FOR TIME INPUTS (Surface) --}}
                                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 text-center">{{ __('Estafet Surface') }}</h3>
                                    {{-- Changed sm:flex-row to sm:flex-wrap and added mb-4 to the first row --}}
                                    <div class="flex flex-col sm:flex-row sm:flex-wrap justify-center items-start gap-4">

                                        {{-- Row 1: SF 4x50m and SF 4x100m Group --}}
                                        <div class="flex flex-col sm:flex-row justify-center items-start w-full sm:mb-4"> {{-- New wrapper for the first row --}}
                                            {{-- SF 4x50m Group --}}
                                            <div id="SF_4x50m_container" class="p-2 hidden flex-1 sm:w-1/2 border-r sm:border-r border-gray-300 dark:border-gray-600 pr-4">
                                                <div class="flex items-center mb-4">
                                                    <input type="checkbox" id="SF_4x50m_enable_time_chkbx" name="SF_4x50m_enable_time_chkbx" class="form-checkbox text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                    <label for="SF_4x50m_enable_time_chkbx" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('4x50m') }}</label>
                                                </div>
                                                <div id="SF_4x50m_time_fields" class="flex gap-2 hidden justify-center">
                                                    <div class="flex flex-col items-center">
                                                        <label for="SF_4x50m_mm_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('MM') }}</label>
                                                        <input type="text" id="SF_4x50m_mm_txtbx" name="SF_4x50m_mm_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="SF_4x50m_ss_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('SS') }}</label>
                                                        <input type="text" id="SF_4x50m_ss_txtbx" name="SF_4x50m_ss_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="SF_4x50m_hs_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('HS') }}</label>
                                                        <input type="text" id="SF_4x50m_hs_txtbx" name="SF_4x50m_hs_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- SF 4x100m Group --}}
                                            <div id="SF_4x100m_container" class="p-2 hidden flex-1 sm:w-1/2 pl-4">
                                                <div class="flex items-center mb-4">
                                                    <input type="checkbox" id="SF_4x100m_enable_time_chkbx" name="SF_4x100m_enable_time_chkbx" class="form-checkbox text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                    <label for="SF_4x100m_enable_time_chkbx" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('4x100m') }}</label>
                                                </div>
                                                <div id="SF_4x100m_time_fields" class="flex gap-2 hidden justify-center">
                                                    <div class="flex flex-col items-center">
                                                        <label for="SF_4x100m_mm_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('MM') }}</label>
                                                        <input type="text" id="SF_4x100m_mm_txtbx" name="SF_4x100m_mm_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="SF_4x100m_ss_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('SS') }}</label>
                                                        <input type="text" id="SF_4x100m_ss_txtbx" name="SF_4x100m_ss_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="SF_4x100m_hs_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('HS') }}</label>
                                                        <input type="text" id="SF_4x100m_hs_txtbx" name="SF_4x100m_hs_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                </div>
                                            </div>
                                        </div> {{-- End of Row 1 wrapper --}}

                                        {{-- Row 2: SF 4x200m and SF 4x50mMix Group --}}
                                        <div class="flex flex-col sm:flex-row justify-center items-start w-full sm:mt-4"> {{-- New wrapper for the second row, added sm:mt-4 for spacing --}}
                                            {{-- SF 4x200m Group (Previously UW_200m_container, now SF_4x200m_container) --}}
                                            <div id="SF_4x200m_container" class="p-2 hidden flex-1 sm:w-1/2 border-r sm:border-r border-gray-300 dark:border-gray-600 pr-4">
                                                <div class="flex items-center mb-4">
                                                    <input type="checkbox" id="SF_4x200m_enable_time_chkbx" name="SF_4x200m_enable_time_chkbx" class="form-checkbox text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                    <label for="SF_4x200m_enable_time_chkbx" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('4x200m') }}</label>
                                                </div>
                                                <div id="SF_4x200m_time_fields" class="flex gap-2 hidden justify-center">
                                                    <div class="flex flex-col items-center">
                                                        <label for="SF_4x200m_mm_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('MM') }}</label>
                                                        <input type="text" id="SF_4x200m_mm_txtbx" name="SF_4x200m_mm_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="SF_4x200m_ss_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('SS') }}</label>
                                                        <input type="text" id="SF_4x200m_ss_txtbx" name="SF_4x200m_ss_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="SF_4x200m_hs_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('HS') }}</label>
                                                        <input type="text" id="SF_4x200m_hs_txtbx" name="SF_4x200m_hs_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- SF 4x50mMix Group (SF_4x50mMix_container) --}}
                                            <div id="SF_4x50mMix_container" class="p-2 hidden flex-1 sm:w-1/2 pl-4">
                                                <div class="flex items-center mb-4">
                                                    <input type="checkbox" id="SF_4x50mMix_enable_time_chkbx" name="SF_4x50mMix_enable_time_chkbx" class="form-checkbox text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                    <label for="SF_4x50mMix_enable_time_chkbx" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('4x50m Mix') }}</label>
                                                </div>
                                                <div id="SF_4x50mMix_time_fields" class="flex gap-2 hidden justify-center">
                                                    <div class="flex flex-col items-center">
                                                        <label for="SF_4x50mMix_mm_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('MM') }}</label>
                                                        <input type="text" id="SF_4x50mMix_mm_txtbx" name="SF_4x50mMix_mm_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="SF_4x50mMix_ss_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('SS') }}</label>
                                                        <input type="text" id="SF_4x50mMix_ss_txtbx" name="SF_4x50mMix_ss_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="SF_4x50mMix_hs_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('HS') }}</label>
                                                        <input type="text" id="SF_4x50mMix_hs_txtbx" name="SF_4x50mMix_hs_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                </div>
                                            </div>
                                        </div> {{-- End of Row 2 wrapper --}}

                                        {{-- Row 3: SF 4x100mMix and SF 4x200mMix Group --}}
                                        <div class="flex flex-col sm:flex-row justify-center items-start w-full sm:mt-4"> {{-- New wrapper for the second row, added sm:mt-4 for spacing --}}
                                            {{-- SF 4x100mMix Group (Previously UW_800m_container, now SF_4x100mMix_container) --}}
                                            <div id="SF_4x100mMix_container" class="p-2 hidden flex-1 sm:w-1/2 border-r sm:border-r border-gray-300 dark:border-gray-600 pr-4">
                                                <div class="flex items-center mb-4">
                                                    <input type="checkbox" id="SF_4x100mMix_enable_time_chkbx" name="SF_4x100mMix_enable_time_chkbx" class="form-checkbox text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                    <label for="SF_4x100mMix_enable_time_chkbx" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('4x100m Mix') }}</label>
                                                </div>
                                                <div id="SF_4x100mMix_time_fields" class="flex gap-2 hidden justify-center">
                                                    <div class="flex flex-col items-center">
                                                        <label for="SF_4x100mMix_mm_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('MM') }}</label>
                                                        <input type="text" id="SF_4x100mMix_mm_txtbx" name="SF_4x100mMix_mm_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="SF_4x100mMix_ss_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('SS') }}</label>
                                                        <input type="text" id="SF_4x100mMix_ss_txtbx" name="SF_4x100mMix_ss_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="SF_4x100mMix_hs_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('HS') }}</label>
                                                        <input type="text" id="SF_4x100mMix_hs_txtbx" name="SF_4x100mMix_hs_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- SF 4x200mMix Group (Previously UW_1500m_container, now SF_4x200mMix_container) --}}
                                            <div id="SF_4x200mMix_container" class="p-2 hidden flex-1 sm:w-1/2 pl-4">
                                                <div class="flex items-center mb-4">
                                                    <input type="checkbox" id="SF_4x200mMix_enable_time_chkbx" name="SF_4x200mMix_enable_time_chkbx" class="form-checkbox text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                    <label for="SF_4x200mMix_enable_time_chkbx" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('4x200m Mix') }}</label>
                                                </div>
                                                <div id="SF_4x200mMix_time_fields" class="flex gap-2 hidden justify-center">
                                                    <div class="flex flex-col items-center">
                                                        <label for="SF_4x200mMix_mm_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('MM') }}</label>
                                                        <input type="text" id="SF_4x200mMix_mm_txtbx" name="SF_4x200mMix_mm_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="SF_4x200mMix_ss_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('SS') }}</label>
                                                        <input type="text" id="SF_4x200mMix_ss_txtbx" name="SF_4x200mMix_ss_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="SF_4x200mMix_hs_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('HS') }}</label>
                                                        <input type="text" id="SF_4x200mMix_hs_txtbx" name="SF_4x200mMix_hs_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                </div>
                                            </div>
                                        </div> {{-- End of Row 3 wrapper --}}
                                    </div>
                                </div>

                                {{-- NEW CONTAINER FOR TIME INPUTS (Bifin) --}}
                                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm mt-4"> {{-- Added mt-4 for spacing from previous section --}}
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 text-center">{{ __('Estafet Bifin') }}</h3>
                                    <div class="flex flex-col sm:flex-row sm:flex-wrap justify-center items-start gap-4">

                                        {{-- Row 1: BF 4x50m and BF 4x100m Group --}}
                                        <div class="flex flex-col sm:flex-row justify-center items-start w-full sm:mb-4"> {{-- New wrapper for the first row --}}
                                            {{-- BF 4x50m Group --}}
                                            <div id="BF_4x50m_container" class="p-2 hidden flex-1 sm:w-1/2 border-r sm:border-r border-gray-300 dark:border-gray-600 pr-4">
                                                <div class="flex items-center mb-4">
                                                    <input type="checkbox" id="BF_4x50m_enable_time_chkbx" name="BF_4x50m_enable_time_chkbx" class="form-checkbox text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                    <label for="BF_4x50m_enable_time_chkbx" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('4x50m') }}</label>
                                                </div>
                                                <div id="BF_4x50m_time_fields" class="flex gap-2 hidden justify-center">
                                                    <div class="flex flex-col items-center">
                                                        <label for="BF_4x50m_mm_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('MM') }}</label>
                                                        <input type="text" id="BF_4x50m_mm_txtbx" name="BF_4x50m_mm_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="BF_4x50m_ss_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('SS') }}</label>
                                                        <input type="text" id="BF_4x50m_ss_txtbx" name="BF_4x50m_ss_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="BF_4x50m_hs_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('HS') }}</label>
                                                        <input type="text" id="BF_4x50m_hs_txtbx" name="BF_4x50m_hs_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- BF 4x100m Group --}}
                                            <div id="BF_4x100m_container" class="p-2 hidden flex-1 sm:w-1/2 pl-4">
                                                <div class="flex items-center mb-4">
                                                    <input type="checkbox" id="BF_4x100m_enable_time_chkbx" name="BF_4x100m_enable_time_chkbx" class="form-checkbox text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                    <label for="BF_4x100m_enable_time_chkbx" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('4x100m') }}</label>
                                                </div>
                                                <div id="BF_4x100m_time_fields" class="flex gap-2 hidden justify-center">
                                                    <div class="flex flex-col items-center">
                                                        <label for="BF_4x100m_mm_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('MM') }}</label>
                                                        <input type="text" id="BF_4x100m_mm_txtbx" name="BF_4x100m_mm_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="BF_4x100m_ss_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('SS') }}</label>
                                                        <input type="text" id="BF_4x100m_ss_txtbx" name="BF_4x100m_ss_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="BF_4x100m_hs_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('HS') }}</label>
                                                        <input type="text" id="BF_4x100m_hs_txtbx" name="BF_4x100m_hs_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                </div>
                                            </div>
                                        </div> {{-- End of Row 1 wrapper --}}

                                        {{-- Row 2: BF 4x200m and BF 4x50mMix Group --}}
                                        <div class="flex flex-col sm:flex-row justify-center items-start w-full sm:mt-4"> {{-- New wrapper for the second row, added sm:mt-4 for spacing --}}
                                            {{-- BF 4x200m Group --}}
                                            <div id="BF_4x200m_container" class="p-2 hidden flex-1 sm:w-1/2 border-r sm:border-r border-gray-300 dark:border-gray-600 pr-4">
                                                <div class="flex items-center mb-4">
                                                    <input type="checkbox" id="BF_4x200m_enable_time_chkbx" name="BF_4x200m_enable_time_chkbx" class="form-checkbox text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                    <label for="BF_4x200m_enable_time_chkbx" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('4x200m') }}</label>
                                                </div>
                                                <div id="BF_4x200m_time_fields" class="flex gap-2 hidden justify-center">
                                                    <div class="flex flex-col items-center">
                                                        <label for="BF_4x200m_mm_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('MM') }}</label>
                                                        <input type="text" id="BF_4x200m_mm_txtbx" name="BF_4x200m_mm_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="BF_4x200m_ss_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('SS') }}</label>
                                                        <input type="text" id="BF_4x200m_ss_txtbx" name="BF_4x200m_ss_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="BF_4x200m_hs_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('HS') }}</label>
                                                        <input type="text" id="BF_4x200m_hs_txtbx" name="BF_4x200m_hs_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- BF 4x50mMix Group --}}
                                            <div id="BF_4x50mMix_container" class="p-2 hidden flex-1 sm:w-1/2 pl-4">
                                                <div class="flex items-center mb-4">
                                                    <input type="checkbox" id="BF_4x50mMix_enable_time_chkbx" name="BF_4x50mMix_enable_time_chkbx" class="form-checkbox text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                    <label for="BF_4x50mMix_enable_time_chkbx" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('4x50m Mix') }}</label>
                                                </div>
                                                <div id="BF_4x50mMix_time_fields" class="flex gap-2 hidden justify-center">
                                                    <div class="flex flex-col items-center">
                                                        <label for="BF_4x50mMix_mm_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('MM') }}</label>
                                                        <input type="text" id="BF_4x50mMix_mm_txtbx" name="BF_4x50mMix_mm_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="BF_4x50mMix_ss_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('SS') }}</label>
                                                        <input type="text" id="BF_4x50mMix_ss_txtbx" name="BF_4x50mMix_ss_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="BF_4x50mMix_hs_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('HS') }}</label>
                                                        <input type="text" id="BF_4x50mMix_hs_txtbx" name="BF_4x50mMix_hs_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                </div>
                                            </div>
                                        </div> {{-- End of Row 2 wrapper --}}

                                        {{-- Row 3: BF 4x100mMix and BF 4x200mMix Group --}}
                                        <div class="flex flex-col sm:flex-row justify-center items-start w-full sm:mt-4"> {{-- New wrapper for the third row, added sm:mt-4 for spacing --}}
                                            {{-- BF 4x100mMix Group --}}
                                            <div id="BF_4x100mMix_container" class="p-2 hidden flex-1 sm:w-1/2 border-r sm:border-r border-gray-300 dark:border-gray-600 pr-4">
                                                <div class="flex items-center mb-4">
                                                    <input type="checkbox" id="BF_4x100mMix_enable_time_chkbx" name="BF_4x100mMix_enable_time_chkbx" class="form-checkbox text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                    <label for="BF_4x100mMix_enable_time_chkbx" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('4x100m Mix') }}</label>
                                                </div>
                                                <div id="BF_4x100mMix_time_fields" class="flex gap-2 hidden justify-center">
                                                    <div class="flex flex-col items-center">
                                                        <label for="BF_4x100mMix_mm_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('MM') }}</label>
                                                        <input type="text" id="BF_4x100mMix_mm_txtbx" name="BF_4x100mMix_mm_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="BF_4x100mMix_ss_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('SS') }}</label>
                                                        <input type="text" id="BF_4x100mMix_ss_txtbx" name="BF_4x100mMix_ss_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="BF_4x100mMix_hs_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('HS') }}</label>
                                                        <input type="text" id="BF_4x100mMix_hs_txtbx" name="BF_4x100mMix_hs_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- BF 4x200mMix Group --}}
                                            <div id="BF_4x200mMix_container" class="p-2 hidden flex-1 sm:w-1/2 pl-4">
                                                <div class="flex items-center mb-4">
                                                    <input type="checkbox" id="BF_4x200mMix_enable_time_chkbx" name="BF_4x200mMix_enable_time_chkbx" class="form-checkbox text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                    <label for="BF_4x200mMix_enable_time_chkbx" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('4x200m Mix') }}</label>
                                                </div>
                                                <div id="BF_4x200mMix_time_fields" class="flex gap-2 hidden justify-center">
                                                    <div class="flex flex-col items-center">
                                                        <label for="BF_4x200mMix_mm_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('MM') }}</label>
                                                        <input type="text" id="BF_4x200mMix_mm_txtbx" name="BF_4x200mMix_mm_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="BF_4x200mMix_ss_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('SS') }}</label>
                                                        <input type="text" id="BF_4x200mMix_ss_txtbx" name="BF_4x200mMix_ss_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                    <div class="flex flex-col items-center">
                                                        <label for="BF_4x200mMix_hs_txtbx" class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('HS') }}</label>
                                                        <input type="text" id="BF_4x200mMix_hs_txtbx" name="BF_4x200mMix_hs_txtbx" placeholder="00" maxlength="2"
                                                            class="w-10 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-1">
                                                    </div>
                                                </div>
                                            </div>
                                        </div> {{-- End of Row 3 wrapper --}}
                                    </div>
                                </div>

                                {{-- Right Section: Daftar Entri Table --}}
                                <div class="md:col-span-1 bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm flex flex-col" style="min-height: 400px;">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Daftar Entri') }}</h3>
                                    <div class="overflow-x-auto rounded-lg shadow flex-grow">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600" id="daftarEntriTable"> {{-- Add an ID to the table --}}
                                            <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0 z-10">
                                                <tr>
                                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('GENDER') }}</th>
                                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('KU') }}</th>
                                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NAMA_REGU') }}</th>
                                                    <!-- <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NAMACLUB') }}</th> -->
                                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('JENISDOM') }}</th>
                                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NAMAKOTADOM') }}</th>
                                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NAMAPROPDOM') }}</th>
                                                    {{-- Add hidden columns for SP, TGLLAHIR, email, and MON times if they are part of the table display logic or needed for matching --}}
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600 text-sm">
                                                @forelse($daftarEntriList as $entry)
                                                {{-- Add 'data-' attributes to the row for easy JavaScript access --}}
                                                <tr class="entry-row cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                                    data-id-a3="{{ $entry->IDA3P }}"
                                                    data-namaatlet="{{ $entry->NAMAATLET }}"
                                                    data-gender="{{ $entry->GENDER }}"
                                                    data-ku="{{ $entry->KU }}"
                                                    data-sp="{{ $entry->SP == 1 ? 'SP' : 'BUKAN_SP' }}"
                                                    data-namaclub="{{ $entry->NAMACLUB }}"
                                                    data-jenisdom="{{ $entry->JENISDOM }}"
                                                    data-namakotadom="{{ $entry->NAMAKOTADOM }}"
                                                    data-namapropdom="{{ $entry->NAMAPROPDOM }}"
                                                    data-tgllahir="{{ $entry->TGLLAHIR ? \Carbon\Carbon::parse($entry->TGLLAHIR)->format('Y-m-d') : '' }}" {{-- Format date for consistency --}}
                                                    data-email="{{ $entry->email }}"
                                                    data-mon50mm="{{ $entry->MON50MM ?? '' }}"
                                                    data-mon50ss="{{ $entry->MON50SS ?? '' }}"
                                                    data-mon50hs="{{ $entry->MON50HS ?? '' }}"
                                                    {{-- Add other MON/SUB/IMM fields as needed --}}>
                                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $entry->GENDER }}</td>
                                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $entry->KU }}</td>
                                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $entry->NAMAATLET }}</td>
                                                    <!-- <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $entry->NAMACLUB }}</td> -->
                                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $entry->JENISDOM }}</td>
                                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $entry->NAMAKOTADOM }}</td>
                                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $entry->NAMAPROPDOM }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                                        {{ __('Belum ada entri nomor estafet.') }}
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- Kontingen Summary Section --}}
                                    <div class="mt-4 text-sm font-mono text-gray-900 dark:text-gray-300 border-t border-gray-200 dark:border-gray-600 pt-4">
                                        <h4 class="font-semibold mb-2">{{ __('KONTINGEN') }}</h4>
                                        <div>{{ __('Atlet Pa : ') }}{{ $kontingenSummary['atletPa'] }}</div>
                                        <div>{{ __('Atlet Pi : ') }}{{ $kontingenSummary['atletPi'] }}</div>
                                        <div class="mt-2 text-base font-bold">{{ __('TOTAL : ') }}{{ $kontingenSummary['totalAtlet'] }} {{ __('Atlet') }}</div>
                                        <div>{{ __('SP = ') }}{{ $kontingenSummary['totalSp'] }} {{ __('Atlet') }}</div>
                                    </div>
                                </div>
                            </div>


                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-6 flex flex-wrap items-center justify-end gap-x-4 gap-y-2">
                            <!-- <button type="button" class="rounded-md bg-gray-200 dark:bg-gray-700 px-4 py-2 text-sm font-semibold text-gray-800 dark:text-gray-300 shadow-sm hover:bg-gray-300 dark:hover:bg-gray-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ __('Keluar') }}</button> -->
                            <button type="button" id="hapus-regu-button" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">{{ __('Hapus') }}</button>
                            <!-- <button type="button" class="rounded-md bg-yellow-400 px-4 py-2 text-sm font-semibold text-gray-800 shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-500">{{ __('Batal') }}</button> -->
                            <!-- <button type="button" class="rounded-md bg-blue-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500">{{ __('Ubah') }}</button> -->
                            <button type="button" id="simpan-regu-button" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ __('Simpan') }}</button>
                            <!-- <button type="button" class="rounded-md bg-green-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500">{{ __('Tambah') }}</button> -->
                        </div>
                        {{-- hidden fields to store the selected record's ID and name --}}
                        <input type="hidden" id="selected-a3-id" value="">
                        <input type="hidden" id="selected-a3-name" value="">
                    </form>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Pass server-side data to JavaScript
        window.atletDetails = @json($atletDetailsForJs ?? []);
        window.currentUserEmail = "{{ Auth::user()->email ?? '' }}";
        window.existingA3Entries = @json($existingA3Entries);

        // Pass kontingen details for the Estafet form
        window.currentUserJenisDom = @json(Auth::user() -> JENISDOM ?? null);
        window.currentUserNamaKotaDom = @json(Auth::user() -> NAMAKOTADOM ?? null);
        window.currentUserNamaPropDom = @json(Auth::user() -> NAMAPROPDOM ?? null);
        window.currentUserNamaClub = @json(Auth::user() -> NAMACLUB ?? null);
        window.currentUserJnsKompetisi = @json(Auth::user() -> JNSKOMPETISI ?? null);

        // Element references for form a3 noestafet
        const kuSelectEstafet = $('#ku_select_estafet');
        const genderPaRadioEstafet = $('#gender_estafet_pa');
        const genderPiRadioEstafet = $('#gender_estafet_pi');
        const genderMixRadioEstafet = $('#gender_estafet_mix');
        const spStatusEstafet = $('input[name="sp_status_estafet"]');
        const namaReguInput = $('#nama_regu');
        const simpanReguButton = $('#simpan-regu-button');

        // Time input references
        const sf4x50mEnableCheckbox = $('#SF_4x50m_enable_time_chkbx');
        const sf4x50mMmInput = $('#SF_4x50m_mm_txtbx');
        const sf4x50mSsInput = $('#SF_4x50m_ss_txtbx');
        const sf4x50mHsInput = $('#SF_4x50m_hs_txtbx');
        // ... define all other SF, BF, Mix time input references here ...

        console.log('All script data and element references are ready.');
    </script>
    @endpush

</x-app-layout>
