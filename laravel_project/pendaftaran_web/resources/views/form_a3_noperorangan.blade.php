<x-app-layout>
    <x-slot name="title">{{ __('Entri Form A3 - Nomor Perorangan') }}</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Form A3 - Nomor Perorangan') }}
        </h2>
    </x-slot>

    {{-- Pass athlete data to JavaScript --}}
    <script>
        window.atletDetails = @json($atletDetailsForJs ?? []);
        console.log('Atlet Details for JS:', window.atletDetails); // For debugging
    </script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-full">
                    <form id="form-a3-perorangan" action="#" method="POST"> {{-- Action will be updated later --}}
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Left Section --}}
                            <div class="md:col-span-2 space-y-4">
                                {{-- Nama Atlet Section --}}
                                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Nama Atlet') }}</h3>
                                    <div class="flex flex-col sm:flex-row items-center gap-4 mb-4">
                                        <div class="flex-1 w-full">
                                            <label for="nama_atlet_input" class="sr-only">{{ __('Nama Atlet') }}</label>
                                            <!-- <input type="text" id="nama_atlet_input" name="nama_atlet_input" placeholder="Nama Atlet"
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"> -->
                                            <!-- <select name="nama_atlet_input" id="nama_atlet_input" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">Pilih Atlet</option>
                                                @foreach($NamaAtletList as $atletNames)
                                                <option value="{{ $atletNames }}" {{ old('ku') == $atletNames ? 'selected' : '' }}>
                                                    {{ $atletNames }}
                                                </option>
                                                @endforeach
                                            </select> -->
                                            <select name="selected_atlet_nonias" id="nama_atlet_input"
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">Pilih Atlet</option>
                                                @foreach($NamaAtletList as $idAtlet => $atletName)
                                                <option value="{{ $idAtlet }}">{{ $atletName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="button" id="pilih-atlet-button"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                                            {{ __('Pilih') }}
                                        </button>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-4">
                                        {{-- Gender --}}
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Gender') }}</label>
                                        <div class="flex gap-2">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="gender" value="PA" id="gender_pa" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Pa') }}</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="gender" value="PI" id="gender_pi" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Pi') }}</span>
                                            </label>
                                        </div>
                                        {{-- KU --}}
                                        <label for="ku_select" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('KU') }}</label>
                                        <select id="ku_select" name="ku" class="rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">-- Pilih KU --</option>
                                            {{-- Options will be dynamically loaded or passed from controller --}}
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                            <option value="E">E</option>
                                            <option value="F">F</option>
                                        </select>
                                        {{-- SP / Bukan SP --}}
                                        <div class="flex gap-2">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="sp_status" value="SP" id="sp_yes" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('SP') }}</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="sp_status" value="BUKAN_SP" id="sp_no" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Bukan SP') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Club/Location Details Section --}}
                                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="nama_club" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nama Club') }}</label>
                                            <input type="text" id="nama_club" name="nama_club" value="{{ $clubName }}" readonly
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label for="kota_kab" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Kota / Kab') }}</label>
                                            <input type="text" id="kota_kab" name="kota_kab" value="{{ $kotaKab }}" readonly
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div class="col-span-2">
                                            <label for="nama_kota_kab" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nama Kota / Kab') }}</label>
                                            <input type="text" id="nama_kota_kab" name="nama_kota_kab" value="{{ $kotaKab }}" readonly
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
                                            <input type="radio" name="competition_type" value="SP_TANPA_NIAS" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('SP Jika Tanpa NIAS') }}</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="competition_type" value="BEBAS" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Bebas') }}</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="competition_type" value="ANTAR_CLUB" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Antar Club') }}</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="competition_type" value="ANTAR_KOTAKAB" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Antar Kota/Kab') }}</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="competition_type" value="ANTAR_PROPINSI" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Antar Propinsi') }}</span>
                                        </label>
                                    </div>
                                </div>
                                {{-- NEW: Checkbox and Time Inputs Section --}}
                                <div id="time_inputs_container" class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm hidden">
                                    <div class="flex items-center mb-4">
                                        <input type="checkbox" id="enable_time_input" name="enable_time_input" class="form-checkbox text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                        <label for="enable_time_input" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Input Waktu') }}</label>
                                    </div>

                                    <div id="time_fields" class="flex items-center gap-2 hidden">
                                        <label for="mm_input" class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('MM:') }}</label>
                                        <input type="text" id="mm_input" name="mm_input" placeholder="00" maxlength="2"
                                            class="w-16 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                                        <label for="ss_input" class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('SS:') }}</label>
                                        <input type="text" id="ss_input" name="ss_input" placeholder="00" maxlength="2"
                                            class="w-16 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                                        <label for="hs_input" class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('HS:') }}</label>
                                        <input type="text" id="hs_input" name="hs_input" placeholder="00" maxlength="2"
                                            class="w-16 text-center rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                                {{-- Large Empty Area (for future use, e.g., selected perorangan events) --}}
                                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm h-64">
                                    {{-- This area can be used for displaying selected events or other details --}}
                                    <p class="text-gray-500 dark:text-gray-400 text-center py-10">{{ __('Area untuk daftar nomor perorangan yang dipilih.') }}</p>
                                </div>
                            </div>

                            {{-- Right Section: Daftar Entri Table --}}
                            <div class="md:col-span-1 bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm flex flex-col" style="min-height: 400px;">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Daftar Entri') }}</h3>
                                <div class="overflow-x-auto rounded-lg shadow flex-grow">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                        <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0 z-10">
                                            <tr>
                                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('GENDER') }}</th>
                                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('KU') }}</th>
                                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NAMAATLET') }}</th>
                                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NAMACLUB') }}</th>
                                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('JENISDOM') }}</th>
                                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NAMAKOTADOM') }}</th>
                                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NAMAPROPDOM') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600 text-sm">
                                            {{-- Use $daftarEntriList from controller to populate this table --}}
                                            @forelse($daftarEntriList as $entry)
                                            <tr>
                                                <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $entry->GENDER }}</td>
                                                <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $entry->KU }}</td>
                                                <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $entry->NAMAATLET }}</td>
                                                <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $entry->NAMACLUB }}</td>
                                                <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $entry->JENISDOM }}</td>
                                                <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $entry->NAMAKOTADOM }}</td>
                                                <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $entry->NAMAPROPDOM }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                                    {{ __('Belum ada entri nomor perorangan.') }}
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

                        {{-- Action Buttons --}}
                        <div class="mt-6 flex flex-wrap items-center justify-end gap-x-4 gap-y-2">
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
</x-app-layout>
