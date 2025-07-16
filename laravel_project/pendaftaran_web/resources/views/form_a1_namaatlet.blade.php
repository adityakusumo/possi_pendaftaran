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
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        {{-- Left Section: JIKA SUDAH PUNYA NIAS (PESERTA DARI JATIM) --}}
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('JIKA SUDAH PUNYA NIAS (PESERTA DARI JATIM)') }}</h2>

                            <div class="mb-4">
                                <label for="nias_input" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('NIAS') }}</label>
                                <div class="mt-1 flex gap-2">
                                    <input type="text" id="nias_input" name="nias_input" placeholder="Cari NIAS dg Nama Atlet"
                                        class="flex-1 rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <button type="button"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-700 dark:hover:bg-indigo-600">
                                        {{ __('Pilih') }}
                                    </button>
                                </div>
                            </div>

                            {{-- Table for existing participants --}}
                            <div class="overflow-x-auto rounded-lg shadow mt-4" style="max-height: 250px;"> {{-- Added max-height and overflow-y --}}
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                    <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0 z-10"> {{-- Sticky header for scrollable table --}}
                                        <tr>
                                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NAMA CLUB') }}</th>
                                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('JNS') }}</th>
                                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('KOTA CLUB') }}</th>
                                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('PROPINSI CLUB') }}</th>
                                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NAMA ATLET') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                        @forelse($niasList as $niaspeserta)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $niaspeserta->NAMACLUB }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $niaspeserta->JENIS }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $niaspeserta->NAMAKOTA }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $niaspeserta->NAMAPROP }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $niaspeserta->NAMA }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                                {{ __('Belum ada data kontingen yang tersimpan.') }}
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Right Section: JIKA BELUM PUNYA NIAS / PESERTA LUAR JATIM --}}
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('JIKA BELUM PUNYA NIAS / PESERTA LUAR JATIM') }}</h2>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="nama_club_new" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nama Club') }}</label>
                                    <input type="text" id="nama_club_new" name="nama_club_new" value="PENGUIN DC" readonly
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label for="kota_kab_new" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Kota / Kab') }}</label>
                                    <select id="kota_kab_new" name="kota_kab_new"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="SURABAYA">SURABAYA</option>
                                        {{-- Add more options dynamically --}}
                                    </select>
                                </div>
                                <div class="col-span-2"> {{-- Spans full width --}}
                                    <label for="nama_kota_kab_new" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nama Kota / Kab') }}</label>
                                    <input type="text" id="nama_kota_kab_new" name="nama_kota_kab_new" value="SURABAYA" readonly
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label for="propinsi_new" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Propinsi') }}</label>
                                    <select id="propinsi_new" name="propinsi_new"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="JAWA TIMUR">JAWA TIMUR</option>
                                        {{-- Add more options dynamically --}}
                                    </select>
                                </div>
                                <div>
                                    <label for="negara_new" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Negara') }}</label>
                                    <input type="text" id="negara_new" name="negara_new" value="INDONESIA" readonly
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div class="col-span-2"> {{-- Spans full width --}}
                                    <label for="nama_atlet_new" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nama Atlet') }}</label>
                                    <input type="text" id="nama_atlet_new" name="nama_atlet_new"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                {{-- Tanggal Lahir --}}
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Tanggal Lahir') }}</label>
                                    <div class="flex items-center gap-2">
                                        <select name="birth_day" class="rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @for($d = 1; $d <= 31; $d++)
                                                <option value="{{ $d }}" {{ old('birth_day', 18) == $d ? 'selected' : '' }}>{{ $d }}</option>
                                                @endfor
                                        </select>
                                        <select name="birth_month" class="rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @for($m = 1; $m <= 12; $m++)
                                                <option value="{{ $m }}" {{ old('birth_month', 9) == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                                                @endfor
                                        </select>
                                        <select name="birth_year" class="rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @for($y = date('Y'); $y >= 1900; $y--)
                                            <option value="{{ $y }}" {{ old('birth_year', 2011) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                        <select name="ku_d" class="rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="KU D">KU D</option>
                                            {{-- Add more KU options --}}
                                        </select>
                                    </div>
                                </div>

                                {{-- Gender --}}
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Gender') }}</label>
                                    <div class="mt-1 flex gap-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="gender" value="P" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Pria') }}</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="gender" value="W" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600" checked>
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Wanita') }}</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Sparing Partner --}}
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Sparing Partner') }}</label>
                                    <div class="mt-1 flex items-center gap-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="sparing_partner" value="SP" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('SP') }}</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="sparing_partner" value="BUKAN_SP" class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600" checked>
                                            <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Bukan SP') }}</span>
                                        </label>
                                        <input type="text" name="sparing_partner_number" value="05035785071066"
                                            class="flex-1 rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons above lower table --}}
                    <div class="mt-8 flex flex-wrap justify-center sm:justify-start gap-3 mb-6">
                        <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-sm">{{ __('Antar Club') }}</button>
                        <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-sm">{{ __('Antar Kota/Kab') }}</button>
                        <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-sm">{{ __('Antar Propinsi') }}</button>
                        <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-sm">{{ __('SP JIKA Tanpa NIAS') }}</button>
                        <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-sm">{{ __('Bebas') }}</button>
                    </div>

                    {{-- Lower Table --}}
                    <div class="overflow-x-auto rounded-lg shadow mb-6" style="max-height: 300px;"> {{-- Added max-height and overflow-y --}}
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                            <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0 z-10"> {{-- Sticky header for scrollable table --}}
                                <tr>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NAMACLUB') }}</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('JENISI') }}</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NAMAKOTADOM') }}</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NAMAPROPDOM') }}</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('GENDER') }}</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NAMAATLET') }}</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('SP') }}</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('NISNDA') }}</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('EXP1009') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600 text-sm">
                                {{-- Placeholder Data for lower table --}}
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">PENGUIN DC</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">KOTA</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">SURABAYA</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">JAWA TIMUR</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">P</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">Achmad Jef Al Rofif</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">D</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">05035785071066</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">18/09/2011</td>
                                </tr>
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">PENGUIN DC</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">KOTA</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">SURABAYA</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">JAWA TIMUR</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">P</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">Aldea Balqis Barizah</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">G</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">05035785071066</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">17/04/2013</td>
                                </tr>
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">PENGUIN DC</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">KOTA</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">SURABAYA</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">JAWA TIMUR</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">Pi</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">Nayla Shaheema Ramadhana F</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">0</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">05035785071353</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-gray-900 dark:text-gray-300">13/06/2017</td>
                                </tr>
                                {{-- End Placeholder Data --}}
                            </tbody>
                        </table>
                    </div>

                    {{-- Summary Section --}}
                    <div class="flex flex-col sm:flex-row justify-between items-end mb-8 text-sm font-mono text-gray-900 dark:text-gray-300">
                        <div class="mb-2 sm:mb-0">
                            <div>{{ __('Club : PENGUIN DC') }}</div>
                            <div>{{ __('Atlet Pa : 1') }}</div>
                            <div>{{ __('Atlet Pi : 2') }}</div>
                        </div>
                        <div>
                            <div>{{ __('TOTAL : 3 Atlet') }}</div>
                            <div>{{ __('SP = 0 Atlet') }}</div>
                        </div>
                    </div>

                    {{-- Global Action Buttons --}}
                    <div class="mt-6 flex flex-wrap items-center justify-end gap-x-4 gap-y-2">
                        <button type="button" class="rounded-md bg-gray-200 dark:bg-gray-700 px-4 py-2 text-sm font-semibold text-gray-800 dark:text-gray-300 shadow-sm hover:bg-gray-300 dark:hover:bg-gray-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ __('Keluar') }}</button>
                        <button type="button" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">{{ __('Hapus') }}</button>
                        <button type="button" class="rounded-md bg-yellow-400 px-4 py-2 text-sm font-semibold text-gray-800 shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-500">{{ __('Batal') }}</button>
                        <button type="button" class="rounded-md bg-blue-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500">{{ __('Ubah') }}</button>
                        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ __('Simpan') }}</button>
                        <button type="button" class="rounded-md bg-green-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500">{{ __('Tambah') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
