<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Hitung Biaya Pendaftaran + Deposit + Lain-lain') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Top Section: Club/Location Details & Action Controls --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">

                    {{-- Left Column: Club/Location Inputs --}}
                    <div class="lg:col-span-1 space-y-4">
                        <div>
                            <label for="nama_club" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nama Club') }}</label>
                            <input type="text" id="nama_club" name="nama_club" value="{{ $namaClub }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Kota / Kab') }}</label>
                            <input type="text" id="kota_kab" name="kota_kab" value="{{ $kotaKab }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nama Kota / Kab') }}</label>
                            <input type="text" id="nama_kota_kab" name="nama_kota_kab" value="{{ $namaKotaKab }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Propinsi') }}</label>
                            <input type="text" id="propinsi" name="propinsi" value="{{ $propinsi }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Negara') }}</label>
                            <input type="text" id="negara" name="negara" value="{{ $negara }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 shadow-sm">
                        </div>
                    </div>

                    {{-- Middle Column: Checkboxes/Radio Buttons --}}
                    <div class="lg:col-span-1 space-y-4 pt-2">
                        {{-- SP Type Radios --}}
                        <div class="flex gap-4 pt-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="sp_type_hitung" value="SP_TANPA_NIAS" {{ ($defaultSpType ?? '') === 'SP_TANPA_NIAS' ? 'checked' : '' }} disabled class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('SP Jika Tanpa NIAS') }}</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="sp_type_hitung" value="BEBAS" {{ ($defaultSpType ?? '') === 'BEBAS' ? 'checked' : '' }} disabled class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Bebas') }}</span>
                            </label>
                        </div>

                        {{-- Competition Type Radios --}}
                        <div class="flex gap-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="competition_type_hitung" value="ANTAR_CLUB" {{ ($defaultCompetitionType ?? '') === 'ANTAR_CLUB' ? 'checked' : '' }} disabled class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Antar Club') }}</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="competition_type_hitung" value="ANTAR_KOTAKAB" {{ ($defaultCompetitionType ?? '') === 'ANTAR_KOTAKAB' ? 'checked' : '' }} disabled class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Antar Kota/Kab') }}</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="competition_type_hitung" value="ANTAR_PROPINSI" {{ ($defaultCompetitionType ?? '') === 'ANTAR_PROPINSI' ? 'checked' : '' }} disabled class="form-radio text-indigo-600 dark:bg-gray-800 dark:border-gray-600 dark:checked:bg-indigo-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">{{ __('Antar Propinsi') }}</span>
                            </label>
                        </div>
                    </div>

                    {{-- Right Column: Action Buttons --}}
                    <div class="lg:col-span-1 flex flex-col items-center justify-end gap-4 p-4">
                        <button type="button" class="w-full sm:w-auto rounded-md bg-gray-600 px-6 py-2 text-base font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">{{ __('Keluar') }}</button>
                        <button type="button" class="w-full sm:w-auto rounded-md bg-indigo-600 px-6 py-2 text-base font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{ __('Hitung') }}</button>
                    </div>
                </div>

                {{-- Bottom Section: Data Table and Total Biaya --}}
                <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                    <div class="overflow-x-auto shadow-md sm:rounded-lg mb-4">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-200 dark:bg-gray-600">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Kontingen') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Nomor') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Tarif') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Atlet/Regu') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Jml Nmr') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Pendaftaran') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Deposit') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Daftar+Deposit') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Lain-2') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($biayaList as $biaya)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">{{ $biaya->NAMACLUB }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $biaya->NOMOR }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $biaya->RPTARIF }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $biaya->JMLATLET }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $biaya->JMLNOLOMBA }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $biaya->RPTOTDAFTAR }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $biaya->RPDEPOSIT }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $biaya->RPTOTDAFTDEPO }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $biaya->RPPLAIN }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">{{ $biaya->RPTOTAL }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Total Biaya Section --}}
                    <div class="flex justify-end items-center mt-4 text-lg font-bold text-gray-900 dark:text-gray-100">
                        <span class="mr-2">{{ __('Total Biaya (Pendaftaran + Deposit + Lain-2) (Rp) :') }}</span>
                        <span id="total_biaya_display" class="text-2xl text-indigo-600 dark:text-indigo-400">550,000</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
