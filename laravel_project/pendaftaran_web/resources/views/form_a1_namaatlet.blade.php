{{-- Ensure jQuery is loaded first --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- Then, set your global JavaScript data --}}
<script>
    window.mstKuData = @json($mstKuData ?? []);
</script>

<!-- {{-- Finally, load your Vite-managed JavaScript files (which may depend on jQuery and window.mstKuData) --}}
@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/form_a1_namaatlet.js']) -->

<x-app-layout>
    <x-slot name="title">{{ __('Entri Form A1 - Daftar Atlet') }}</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Form A1-Daftar Atlet') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-full">
                    <!-- {{-- Hidden form for delete action --}}
                    {{-- This form should be outside the main form for clarity, or handle deletion via AJAX --}}
                    <form id="delete-atlet-form" method="POST" action="{{ route('atlet.destroyAtlet') }}" style="display: none;">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="nonias_to_delete" id="nonias_to_deletenias">
                    </form> -->
                    <form id="delete-atlet-form" method="POST" action="{{ route('atlet.destroyAtlet') }}" class="hidden">
                        @csrf
                        @method('DELETE') {{-- This tells Laravel it's a DELETE request --}}
                        <input type="hidden" name="nonias_to_delete" id="nonias_to_delete_hidden">
                    </form>

                    {{-- MAIN FORM STARTS HERE: This form will encompass all data to be submitted for an Atlet --}}
                    <form id="main-form-a1-namaatlet" action="{{ route('atlet.saveAtlet') }}" method="POST">
                        @csrf

                        {{-- Hidden inputs for the selected NIAS athlete's primary key and EXP1009 --}}
                        <input type="hidden" name="selected_nias_nonias" id="selected_nias_nonias">
                        <!-- <input type="hidden" name="selected_nias_exp1009" id="selected_nias_exp1009"> -->
                        <input type="hidden" name="selected_nias_expired" id="selected_nias_expired">

                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                            {{-- Left Section: JIKA SUDAH PUNYA NIAS (PESERTA DARI JATIM) --}}
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('JIKA SUDAH PUNYA NIAS (PESERTA DARI JATIM)') }}</h2>

                                <div class="mb-4">
                                    <label for="nias_input" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('NIAS') }}</label>
                                    <div class="mt-1 flex gap-2">
                                        <input type="text" id="nias_input" name="nias_search_input" placeholder="Ketik NIAS atau Nama Atlet..."
                                            class="flex-1 rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <button type="button" id="search-nias-button"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                                            {{ __('Cari') }}
                                        </button>
                                    </div>
                                </div>

                                <!-- <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-md">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Selected Athlete Details (for debugging)</h3>
                                    <pre id="selected-athlete-details" class="whitespace-pre-wrap text-sm text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-800 p-3 rounded-md">
                                        No athlete selected yet. Click a row to see details.
                                    </pre>
                                </div> -->

                                <div class="overflow-x-auto shadow-md sm:rounded-lg" style="max-height: 300px; overflow-y: auto;">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                        <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0 z-10">
                                            <tr>
                                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NO') }}</th>
                                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NAMA CLUB') }}</th>
                                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('JNS') }}</th>
                                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('KOTA CLUB') }}</th>
                                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('PROPINSI CLUB') }}</th>
                                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NAMA ATLET') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="nias-table-body" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                            @forelse($niasList as $niaspeserta)
                                            <tr class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 selectable-row"
                                                data-athlete-id="{{ $niaspeserta->ID ?? $loop->iteration }}"
                                                data-athlete-details="{{ json_encode($niaspeserta) }}">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                                    {{ ($niasList->currentPage() - 1) * $niasList->perPage() + $loop->iteration }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $niaspeserta->NAMACLUB ?? '' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $niaspeserta->JENIS ?? '' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $niaspeserta->NAMAKOTA ?? '' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $niaspeserta->NAMAPROP ?? '' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $niaspeserta->NAMA ?? '' }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                                    {{ __('Belum ada data NIAS yang ditemukan.') }}
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Pagination Links --}}
                                <div class="mt-4">
                                    {{ $niasList->links() }}
                                </div>
                            </div> {{-- End of "JIKA SUDAH PUNYA NIAS" section --}}

                            {{-- Right Section: DATA ATLET --}}
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm mt-6">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('DATA ATLET') }}</h2>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="nama_club_new" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nama Club') }}</label>
                                        {{-- IMPORTANT: Changed name to 'nama_club' for controller --}}
                                        <input type="text" id="nama_club_new" name="nama_club"
                                            {{-- value="{{ old('nama_club', $autoFillDetails['NAMACLUB'] ?? '') }}" readonly --}}
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="kota_kab_new" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Kota / Kab') }}</label>
                                        {{-- IMPORTANT: Changed name to 'jenis_kota_kab' for controller --}}
                                        <input type="text" id="kota_kab_new" name="jenis_kota_kab"
                                            {{-- value="{{ old('jenis_kota_kab', $autoFillDetails['JENIS'] ?? '') }}" --}}
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div class="col-span-2"> {{-- Spans full width --}}
                                        <label for="nama_kota_kab_new" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nama Kota / Kab') }}</label>
                                        {{-- IMPORTANT: Changed name to 'nama_kota_kab' for controller --}}
                                        <input type="text" id="nama_kota_kab_new" name="nama_kota_kab"
                                            {{-- value="{{ old('nama_kota_kab', $autoFillDetails['NAMAKOTA'] ?? '') }}" --}}
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="propinsi_new" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Propinsi') }}</label>
                                        {{-- IMPORTANT: Changed name to 'propinsi' for controller --}}
                                        <input type="text" id="propinsi_new" name="propinsi"
                                            {{-- value="{{ old('propinsi', $autoFillDetails['NAMAPROP'] ?? '') }}" --}}
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="negara_new" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Negara') }}</label>
                                        {{-- IMPORTANT: Changed name to 'negara' for controller --}}
                                        <input type="text" id="negara_new" name="negara" readonly
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div class="col-span-2"> {{-- Spans full width --}}
                                        <label for="nama_atlet_new" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nama Atlet') }}</label>
                                        {{-- IMPORTANT: Changed name to 'nama_atlet' for controller --}}
                                        <input type="text" id="nama_atlet_new" name="nama_atlet"
                                            {{-- value="{{ old('nama_atlet', $autoFillDetails['NAMA'] ?? '') }}" --}}
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    {{-- Tanggal Lahir --}}
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Tanggal Lahir') }}</label>
                                        <div class="flex items-center gap-2">
                                            <select name="birth_day" id="birth_day" class="rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">Hari</option>
                                                @for($d = 1; $d <= 31; $d++)
                                                    <option value="{{ sprintf('%02d', $d) }}" {{ old('birth_day') == $d ? 'selected' : '' }}>{{ sprintf('%02d', $d) }}</option>
                                                    @endfor
                                            </select>
                                            <select name="birth_month" id="birth_month" class="rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">Bulan</option>
                                                @for($m = 1; $m <= 12; $m++)
                                                    <option value="{{ sprintf('%02d', $m) }}" {{ old('birth_month') == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                                                    @endfor
                                            </select>
                                            <select name="birth_year" id="birth_year" class="rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">Tahun</option>
                                                @for($y = date('Y'); $y >= (date('Y') - 100); $y--)
                                                <option value="{{ $y }}" {{ old('birth_year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                @endfor
                                            </select>
                                            {{-- IMPORTANT: Changed name to 'ku' for controller --}}
                                            <select name="ku" id="ku_d" class="rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">Pilih KU</option>
                                                @foreach($mstKuOptions as $kuValue)
                                                <option value="{{ $kuValue }}" {{ old('ku') == $kuValue ? 'selected' : '' }}>
                                                    {{ $kuValue }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Gender --}}
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Gender') }}</label>
                                        <div class="mt-1 flex gap-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="gender" value="PA" id="gender_pria" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Pria') }}</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="gender" value="PI" id="gender_wanita" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600" checked>
                                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Wanita') }}</span>
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Sparing Partner --}}
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Sparing Partner') }}</label>
                                        <div class="mt-1 flex items-center gap-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="sparing_partner" value="1" id="sparing_partner_yes" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('SP') }}</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="sparing_partner" value="0" id="sparing_partner_no" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600" checked>
                                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Bukan SP') }}</span>
                                            </label>

                                            <!-- {{-- IMPORTANT: This input shows NIAS number, using 'nias_number_display' name --}}
                                            <input type="text" name="nias_number_display" id="nias_number" value="" readonly
                                                class="flex-1 rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"> -->
                                        </div>
                                    </div>

                                    <div class="col-span-2">
                                        {{-- IMPORTANT: This input shows NIAS number, using 'nias_number_display' name --}}
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nomor NIAS') }}</label>
                                        <input type="text" name="nias_number_display" id="nias_number" value="" readonly
                                            class="flex-1 rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    {{-- (Wajib NIAS Status) --}}
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status Wajib Nias') }}</label>
                                        <div class="mt-1 flex items-center gap-4">
                                            <input type="text"
                                                id="wajib_nias_status_display"
                                                name="wajib_nias_status_display"
                                                value="{{ $wajibNiasStatusText }}"
                                                readonly
                                                class="px-4 py-2
                                              {{ $wajibNiasStatusText === 'Bebas' ? 'bg-green-600 text-white' : 'bg-blue-600 text-white' }}
                                              rounded-md shadow
                                              dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600
                                              text-sm font-semibold text-center cursor-default
                                              focus:outline-none focus:ring-0">
                                        </div>
                                    </div>

                                    <!-- {{-- NEW: EXP1009 Input --}}
                                    <div>
                                        <label for="exp1009_input" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('EXP1009') }}</label>
                                        {{-- IMPORTANT: Changed name to 'exp1009' for controller --}}
                                        <input type="text" id="exp1009_input" name="exp1009"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" readonly>
                                    </div> -->

                                </div>



                            </div> {{-- End of DATA ATLET section --}}

                            {{-- Lower Table (Display of currently added athletes to this kontingen) --}}
                            <div class="overflow-x-auto rounded-lg shadow mb-6" style="max-height: 300px; overflow-y: auto;">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                    <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0 z-10">
                                        <tr>
                                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NAMACLUB') }}</th>
                                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('JENISDOM') }}</th>
                                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NAMAKOTADOM') }}</th>
                                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NAMAPROPDOM') }}</th>
                                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('GENDER') }}</th>
                                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NAMAATLET') }}</th>
                                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('SP') }}</th>
                                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NONIAS') }}</th>
                                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('EXPIRED') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600 text-sm" id="atlet-list-table-body">
                                        {{-- Use $atletList from controller to populate this table --}}
                                        @forelse($atletList as $atlet)
                                        {{-- Add data-nonias attribute and a class for JS selection --}}
                                        <tr class="atlet-row-selectable cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700" data-nonias="{{ $atlet->NONIAS }}">
                                            <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $atlet->NAMACLUB }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $atlet->JENISDOM }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $atlet->NAMAKOTADOM }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $atlet->NAMAPROPDOM }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $atlet->GENDER }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $atlet->NAMAATLET }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $atlet->SP }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $atlet->NONIAS }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $atlet->EXPIRED }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                                {{ __('Belum ada atlet yang ditambahkan ke kontingen ini.') }}
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Summary Section (these will need dynamic values from your controller) --}}
                            <div class="flex flex-col sm:flex-row justify-between items-end mb-8 text-sm font-mono text-gray-900 dark:text-gray-300">
                                <div class="mb-2 sm:mb-0">
                                    <div>{{ __('Club : PENGUIN DC') }}</div> {{-- Replace with dynamic club data --}}
                                    <div>{{ __('Atlet Pa : 1') }}</div> {{-- Replace with dynamic male count --}}
                                    <div>{{ __('Atlet Pi : 2') }}</div> {{-- Replace with dynamic female count --}}
                                </div>
                                <div>
                                    <div>{{ __('TOTAL : 3 Atlet') }}</div> {{-- Replace with dynamic total count --}}
                                    <div>{{ __('SP = 0 Atlet') }}</div> {{-- Replace with dynamic SP count --}}
                                </div>
                            </div>

                            {{-- Global Action Buttons --}}
                            <div class="mt-6 flex flex-wrap items-center justify-end gap-x-4 gap-y-2">
                                <button type="button" class="rounded-md bg-gray-200 dark:bg-gray-700 px-4 py-2 text-sm font-semibold text-gray-800 dark:text-gray-300 shadow-sm hover:bg-gray-300 dark:hover:bg-gray-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ __('Keluar') }}</button>
                                <!-- <button type="button" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600" onclick="confirmDeleteAtlet()">{{ __('Hapus') }}</button> -->
                                <!-- <button type="button" class="rounded-md bg-yellow-400 px-4 py-2 text-sm font-semibold text-gray-800 shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-500">{{ __('Batal') }}</button> -->
                                <!-- <button type="button" class="rounded-md bg-blue-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500">{{ __('Ubah') }}</button> -->
                                <!-- <button type="button" class="rounded-md bg-green-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500">{{ __('Tambah') }}</button> -->
                                {{-- Ensure you have a button to trigger the delete --}}
                                <button type="button" onclick="confirmDeleteAtlet()"
                                    class="rounded-md bg-red-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                                    Hapus Atlet
                                </button>
                                <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ __('Simpan') }}</button>
                            </div>
                        </div>
                    </form>
                    {{-- MAIN FORM ENDS HERE --}}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    {{-- Set your global JavaScript data for this specific page --}}
    <script>
        window.mstKuData = @json($mstKuData ?? []);
    </script>

    {{-- Load your page-specific JS file.
             If 'app.js' already imports 'form_a1_namaatlet.js', you might not need to list it here again.
             If it's truly a standalone file not bundled by app.js, list it.
             For clarity, let's assume form_a1_namaatlet.js is already imported by app.js or directly loaded by Vite.
             If it's not imported, you might need:
             @vite(['resources/js/form_a1_namaatlet.js'])
        --}}
    {{--
            IMPORTANT: If resources/js/app.js *already* imports resources/js/form_a1_namaatlet.js,
            then you only need to ensure app.js is loaded (usually in layout's head or body).
            If form_a1_namaatlet.js is standalone and not imported by app.js, then you can add it here.
        --}}
    {{-- Assuming app.js is already loaded globally by @vite in app.blade.php --}}
    {{-- So, you only need to ensure form_a1_namaatlet.js is imported in app.js --}}
    {{-- Or, if it's truly isolated and not imported by app.js, you can add it here: --}}
    {{-- @vite(['resources/js/form_a1_namaatlet.js']) --}}

    {{-- If form_a1_namaatlet.js needs to be loaded separately AND depends on jQuery and mstKuData --}}
    {{-- Make sure app.js isn't trying to load it again via @vite in the layout --}}

    {{-- No need to include jQuery here again if it's in the layout. --}}

    <script>
        // Any inline scripts specific to this page can go here, after all files are loaded.
        // e.g., if you had a specific initialization function for this page
        // myPageInitFunction();
    </script>

    <script>
        // Define global JavaScript variables for routes and translations
        window.appConfig = {
            routes: {
                niasSearch: "{{ route('atlet.niasSearch') }}"
            },
            translations: {
                noNiasData: "{{ __('Tidak ada data NIAS yang ditemukan.') }}",
                failedToLoadNias: "{{ __('Gagal memuat data NIAS. Coba lagi.') }}"
            }
        };
    </script>
    @endpush

    <!-- @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.getElementById('nias-table-body');
            const detailsDisplay = document.getElementById('selected-athlete-details');
            let selectedRow = null;

            // Form elements
            const form = document.querySelector('.bg-gray-100.dark\\:bg-gray-700.p-4.rounded-lg.shadow-sm'); // Select the form container
            const namaClubNew = form.querySelector('#nama_club_new');
            const kotaKabNew = form.querySelector('#kota_kab_new');
            const namaKotaKabNew = form.querySelector('#nama_kota_kab_new');
            const propinsiNew = form.querySelector('#propinsi_new');
            const negaraNew = form.querySelector('#negara_new');
            const namaAtletNew = form.querySelector('#nama_atlet_new');

            const birthDaySelect = form.querySelector('#birth_day');
            const birthMonthSelect = form.querySelector('#birth_month');
            const birthYearSelect = form.querySelector('#birth_year');

            const kuDSelect = form.querySelector('#ku_d'); // Assuming there's a ku_d column in NIAS

            const genderPriaRadio = form.querySelector('#gender_pria');
            const genderWanitaRadio = form.querySelector('#gender_wanita');

            const sparingPartnerYesRadio = form.querySelector('#sparing_partner_yes');
            const sparingPartnerNoRadio = form.querySelector('#sparing_partner_no');
            // const sparingPartnerNumber = form.querySelector('#sparing_partner_number');
            const niasNumber = form.querySelector('#nias_number'); // UPDATED: Renamed variable


            const mstKuData = @json($mstKuData);
            console.log("MstKU Data for comparison:", mstKuData);

            tableBody.addEventListener('click', function(event) {
                const clickedRow = event.target.closest('.selectable-row');

                if (clickedRow) {
                    // Remove 'active' class from previously selected row
                    if (selectedRow) {
                        selectedRow.classList.remove('bg-blue-100', 'dark:bg-blue-900');
                    }

                    // Add 'active' class to the newly selected row
                    clickedRow.classList.add('bg-blue-100', 'dark:bg-blue-900');
                    selectedRow = clickedRow;

                    const rawDataAttribute = clickedRow.dataset.athleteDetails;

                    try {
                        const athleteDetails = JSON.parse(rawDataAttribute);
                        console.log('Parsed athlete details object:', athleteDetails); // For debugging

                        // --- Populate Form Fields ---
                        namaClubNew.value = athleteDetails.NAMACLUB || '';
                        kotaKabNew.value = athleteDetails.JENIS || ''; // Assuming 'NAMAKOTA' from NIAS is used for 'Kota / Kab'
                        namaKotaKabNew.value = athleteDetails.NAMAKOTA || '';
                        propinsiNew.value = athleteDetails.NAMAPROP || '';
                        negaraNew.value = athleteDetails.NEGARA || 'INDONESIA';
                        namaAtletNew.value = athleteDetails.NAMA || '';

                        // Handle Date of Birth (TGLLAHIR) - populate selects and for KU calculation
                        let athleteBirthDate = null;
                        if (athleteDetails.TGLLAHIR) {
                            try {
                                const dob = new Date(athleteDetails.TGLLAHIR);
                                // Set hour to 00:00:00 for accurate date comparison later
                                dob.setHours(0, 0, 0, 0);
                                athleteBirthDate = dob; // Assign to variable for KU comparison

                                if (!isNaN(dob.getDate())) birthDaySelect.value = dob.getDate();
                                if (!isNaN(dob.getMonth())) birthMonthSelect.value = dob.getMonth() + 1;
                                if (!isNaN(dob.getFullYear())) birthYearSelect.value = dob.getFullYear();
                            } catch (e) {
                                console.error('Error parsing TGLLAHIR:', e);
                                birthDaySelect.value = '';
                                birthMonthSelect.value = '';
                                birthYearSelect.value = '';
                            }
                        } else {
                            birthDaySelect.value = '';
                            birthMonthSelect.value = '';
                            birthYearSelect.value = '';
                        }

                        // --- NEW: Handle KU (Kompetisi Usia / Age Group) based on birth date ---
                        if (athleteBirthDate && !isNaN(athleteBirthDate.getTime()) && mstKuData.length > 0) {
                            let kuFound = false;
                            kuDSelect.value = ''; // Clear previous KU selection

                            for (let i = 0; i < mstKuData.length; i++) {
                                const kuEntry = mstKuData[i];

                                // Convert string dates from database (e.g., '2005-01-01') to Date objects
                                // Laravel's date casting often sends 'YYYY-MM-DD' which Date() can parse.
                                const lahirMulai = new Date(kuEntry.LAHIRMULAI);
                                const lahirSampai = new Date(kuEntry.LAHIRSAMPAI);

                                // Ensure comparison dates are valid
                                if (!isNaN(lahirMulai.getTime()) && !isNaN(lahirSampai.getTime())) {
                                    // Normalize KU range dates: start of 'LAHIRMULAI' to end of 'LAHIRSAMPAI'
                                    lahirMulai.setHours(0, 0, 0, 0); // Start of day
                                    lahirSampai.setHours(23, 59, 59, 999); // End of day

                                    // Compare athlete's birth date within the KU range
                                    if (athleteBirthDate >= lahirMulai && athleteBirthDate <= lahirSampai) {
                                        kuDSelect.value = kuEntry.KU; // Set the KU dropdown value
                                        kuFound = true;
                                        break; // Found a match, no need to check further KUs
                                    }
                                } else {
                                    console.warn('Invalid LAHIRMULAI or LAHIRSAMPAI date for KU:', kuEntry.KU);
                                }
                            }

                            if (!kuFound) {
                                console.log("No matching KU found for athlete's birth date:", athleteDetails.TGLLAHIR);
                                kuDSelect.value = ''; // Ensure no KU is selected if no match
                            }
                        } else {
                            // If athlete has no birth date or no MstKU data, ensure KU is cleared
                            kuDSelect.value = '';
                        }
                        // --- END NEW KU LOGIC ---

                        // --- Handle Gender (UPDATED LOGIC) ---
                        // First, uncheck both to ensure a clean state
                        if (genderPriaRadio) genderPriaRadio.checked = false;
                        if (genderWanitaRadio) genderWanitaRadio.checked = false;

                        if (athleteDetails.GENDER) {
                            const genderValue = athleteDetails.GENDER.toUpperCase(); // Convert to uppercase

                            if (genderValue === 'PA') { // Check for 'PA'
                                if (genderPriaRadio) genderPriaRadio.checked = true;
                            } else if (genderValue === 'PI') { // Check for 'PI'
                                if (genderWanitaRadio) genderWanitaRadio.checked = true;
                            }
                            // If it's neither 'PA' nor 'PI', both remain unchecked or you can set a default
                        } else {
                            // If GENDER is null/empty, default to Wanita (or leave both unchecked)
                            if (genderWanitaRadio) genderWanitaRadio.checked = true;
                        }

                        // Handle Sparing Partner
                        // Assuming your NIAS table has a column like 'IS_SP' or 'SPARING_STATUS' (value 'SP' or 'BUKAN_SP')
                        // And another column for the number, e.g., 'SPARING_NUMBER'
                        if (athleteDetails.IS_SP === 'SP' || athleteDetails.SPARING_STATUS === 'SP') { // Adjust column name
                            sparingPartnerYesRadio.checked = true;
                            sparingPartnerNoRadio.checked = false;
                        } else {
                            sparingPartnerYesRadio.checked = false;
                            sparingPartnerNoRadio.checked = true; // Default to 'Bukan SP'
                        }

                        // UPDATED: Populate nias_number with NONIAS column
                        if (niasNumber) {
                            niasNumber.value = athleteDetails.NONIAS || ''; // Set value from NONIAS column
                        }
                        // sparingPartnerNumber.value = athleteDetails.SPARING_NUMBER || ''; // Adjust column name

                        // --- End Populate Form Fields ---


                        // Display the details (formatted for readability) for debugging
                        detailsDisplay.textContent = JSON.stringify(athleteDetails, null, 2);

                    } catch (e) {
                        console.error('Error parsing JSON from data-athlete-details:', e);
                        detailsDisplay.textContent = 'Error: Could not parse athlete details. Check console for more info.';
                    }
                }
            });
        });
    </script>
    @endpush -->
</x-app-layout>
